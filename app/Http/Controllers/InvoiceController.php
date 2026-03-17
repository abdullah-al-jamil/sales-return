<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Invoice::select(['id', 'customer_name', 'invoice_date', 'total_amount', 'status'])
                ->withCount('salesReturns')
                ->orderBy('id', 'desc');

            $recordsTotal = $query->count();

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $recordsFiltered = $query->count();

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $query->skip($start)->take($length);

            if ($request->has('order') && count($request->order) > 0) {
                $columnIndex = $request->order[0]['column'];
                $columnDir = $request->order[0]['dir'];
                $columns = ['id', 'customer_name', 'invoice_date', 'total_amount', 'status'];
                if (isset($columns[$columnIndex])) {
                    $query->orderBy($columns[$columnIndex], $columnDir);
                }
            }

            $invoices = $query->get();

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $invoices->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'customer_name' => $invoice->customer_name,
                        'invoice_date' => $invoice->invoice_date->format('Y-m-d H:i'),
                        'total_amount' => number_format($invoice->total_amount, 2),
                        'status' => $invoice->status,
                        'sales_returns_count' => $invoice->sales_returns_count,
                    ];
                })
            ]);
        }

        return view('invoices.index');
    }

    public function show($id)
    {
        $invoice = Invoice::with(['invoiceItems.item'])->findOrFail($id);

        return response()->json([
            'invoice' => [
                'id' => $invoice->id,
                'customer_name' => $invoice->customer_name,
                'invoice_date' => $invoice->invoice_date->format('Y-m-d H:i'),
                'taxable_amount' => number_format($invoice->taxable_amount, 2),
                'discount_amount' => number_format($invoice->discount_amount, 2),
                'vat_amount' => number_format($invoice->vat_amount, 2),
                'total_amount' => number_format($invoice->total_amount, 2),
                'status' => $invoice->status,
            ],
            'items' => $invoice->invoiceItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item->name,
                    'sku' => $item->item->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => number_format($item->unit_price, 2),
                    'discount' => number_format($item->discount, 2),
                    'tax_rate' => number_format($item->tax_rate, 2),
                    'total' => number_format($item->total, 2),
                ];
            })
        ]);
    }

    public function createReturn($id)
    {
        $invoice = Invoice::with(['invoiceItems.item'])->findOrFail($id);

        return response()->json([
            'invoice' => [
                'id' => $invoice->id,
                'customer_name' => $invoice->customer_name,
                'invoice_date' => $invoice->invoice_date->format('Y-m-d H:i'),
                'total_amount' => number_format($invoice->total_amount, 2),
            ],
            'items' => $invoice->invoiceItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_id' => $item->item_id,
                    'item_name' => $item->item->name,
                    'sku' => $item->item->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => number_format($item->unit_price, 2),
                    'unit_price_raw' => $item->unit_price,
                    'discount' => number_format($item->discount, 2),
                    'discount_raw' => $item->discount,
                    'tax_rate' => number_format($item->tax_rate, 2),
                    'tax_rate_raw' => $item->tax_rate,
                    'total' => number_format($item->total, 2),
                    'total_raw' => $item->total,
                    'taxable_price' => number_format($item->taxable_price, 2),
                    'taxable_price_raw' => $item->taxable_price,
                    'vat_amount' => number_format(($item->taxable_price * $item->tax_rate / 100), 2),
                    'vat_amount_raw' => ($item->taxable_price * $item->tax_rate / 100),
                ];
            })
        ]);
    }

    public function storeReturn(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoiceItemIds = $invoice->invoiceItems->pluck('id');

        $request->validate([
            'return_date' => 'required',
            'reason' => 'required',
            'refund_method' => 'required',
            'items' => 'required|array|min:1',
            'items.*.selected' => 'required',
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($invoiceItemIds) {
                    $index = explode('.', $attribute)[1];
                    $items = request()->items;
                    $invoiceItemId = $items[$index]['invoice_item_id'] ?? null;

                    if (!$invoiceItemId || !isset($items[$index]['selected']) || !$items[$index]['selected']) {
                        return;
                    }

                    if (!in_array($invoiceItemId, $invoiceItemIds->toArray())) {
                        return $fail('Invalid invoice item.');
                    }

                    $invoiceItem = \App\Models\InvoiceItem::find($invoiceItemId);
                    $soldQuantity = $invoiceItem->quantity;

                    $alreadyReturned = \App\Models\SalesReturnItem::where('invoice_item_id', $invoiceItemId)
                        ->sum('quantity');

                    $maxReturnable = $soldQuantity - $alreadyReturned;

                    if ((int) $value > $maxReturnable) {
                        $fail("Return quantity cannot exceed sold quantity. Maximum returnable: {$maxReturnable}");
                    }
                },
            ],
        ]);

        $items = $request->items;
        $taxableAmount = 0;
        $discountAmount = 0;
        $vatAmount = 0;
        $totalAmount = 0;

        $returnItems = [];

        foreach ($items as $itemData) {
            if (isset($itemData['selected']) && $itemData['selected']) {
                $quantity = intval($itemData['quantity']);
                $invoiceItemId = $itemData['invoice_item_id'];
                $itemId = $itemData['item_id'];

                $invoiceItem = \App\Models\InvoiceItem::find($invoiceItemId);
                $unitPrice = $invoiceItem->unit_price;
                $taxRate = $invoiceItem->tax_rate;
                $discount = $invoiceItem->discount;

                $taxablePrice = ($unitPrice - $discount) * $quantity;
                $vatAmt = $taxablePrice * $taxRate / 100;
                $total = $taxablePrice + $vatAmt;

                $taxableAmount += $taxablePrice;
                $discountAmount += $discount * $quantity;
                $vatAmount += $vatAmt;
                $totalAmount += $total;

                $returnItems[] = [
                    'invoice_item_id' => $invoiceItemId,
                    'item_id' => $itemId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'taxable_price' => $taxablePrice / $quantity,
                    'discount' => $discount,
                    'tax_rate' => $taxRate,
                    'vat_amount' => $vatAmt,
                    'total' => $total,
                ];
            }
        }

        $salesReturn = SalesReturn::create([
            'invoice_id' => $invoice->id,
            'customer_name' => $invoice->customer_name,
            'return_date' => $request->return_date,
            'taxable_amount' => $taxableAmount,
            'discount_amount' => $discountAmount,
            'vat_amount' => $vatAmount,
            'total_amount' => $totalAmount,
            'reason' => $request->reason,
            'status' => 'approved',
            'refund_method' => $request->refund_method,
        ]);

        foreach ($returnItems as $item) {
            SalesReturnItem::create(array_merge($item, [
                'sales_return_id' => $salesReturn->id,
            ]));

            $itemModel = \App\Models\Item::find($item['item_id']);
            $itemModel->stock += $item['quantity'];
            $itemModel->save();

            StockTransaction::create([
                'item_id' => $item['item_id'],
                'type' => 'return',
                'reference_id' => $salesReturn->id,
                'quantity' => $item['quantity'],
                'stock_effect' => $item['quantity'],
            ]);

            $stock = Stock::where('item_id', $item['item_id'])->first();
            if ($stock) {
                $stock->stock_in += $item['quantity'];
                $stock->available_stock += $item['quantity'];
                $stock->save();
            }
        }

        $invoice->update(['status' => 'returned']);

        return response()->json([
            'success' => true,
            'message' => 'Sales return created successfully',
            'return_id' => $salesReturn->id,
        ]);
    }
}
