<?php

namespace App\Http\Controllers;

use App\Models\SalesReturn;
use Illuminate\Http\Request;

class SalesReturnController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SalesReturn::select([
                'id',
                'invoice_id',
                'customer_name',
                'return_date',
                'total_amount',
                'status',
                'refund_method',
            ])->orderBy('id', 'desc');

            if ($request->has('start_date') && !empty($request->start_date)) {
                $query->whereDate('return_date', '>=', $request->start_date);
            }

            if ($request->has('end_date') && !empty($request->end_date)) {
                $query->whereDate('return_date', '<=', $request->end_date);
            }

            if ($request->has('customer') && !empty($request->customer)) {
                $query->where('customer_name', 'like', '%' . $request->customer . '%');
            }

            if ($request->has('invoice_no') && !empty($request->invoice_no)) {
                $query->where('invoice_id', 'like', '%' . $request->invoice_no . '%');
            }

            $recordsTotal = $query->count();

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%")
                      ->orWhere('invoice_id', 'like', "%{$search}%")
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
                $columns = ['id', 'invoice_id', 'customer_name', 'return_date', 'total_amount', 'status', 'refund_method'];
                if (isset($columns[$columnIndex])) {
                    $query->orderBy($columns[$columnIndex], $columnDir);
                }
            }

            $salesReturns = $query->get();

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $salesReturns->map(function ($return) {
                    return [
                        'id' => $return->id,
                        'invoice_id' => $return->invoice_id,
                        'customer_name' => $return->customer_name,
                        'return_date' => $return->return_date->format('Y-m-d H:i'),
                        'total_amount' => number_format($return->total_amount, 2),
                        'status' => $return->status,
                        'refund_method' => $return->refund_method,
                    ];
                })
            ]);
        }

        return view('sales-returns.index');
    }

    public function show($id)
    {
        $salesReturn = SalesReturn::with(['invoice', 'salesReturnItems.item'])->findOrFail($id);

        return response()->json([
            'sales_return' => [
                'id' => $salesReturn->id,
                'invoice_id' => $salesReturn->invoice_id,
                'customer_name' => $salesReturn->customer_name,
                'return_date' => $salesReturn->return_date->format('Y-m-d H:i'),
                'reason' => $salesReturn->reason,
                'taxable_amount' => number_format($salesReturn->taxable_amount, 2),
                'discount_amount' => number_format($salesReturn->discount_amount, 2),
                'vat_amount' => number_format($salesReturn->vat_amount, 2),
                'total_amount' => number_format($salesReturn->total_amount, 2),
                'status' => $salesReturn->status,
                'refund_method' => $salesReturn->refund_method,
            ],
            'items' => $salesReturn->salesReturnItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item->name,
                    'sku' => $item->item->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => number_format($item->unit_price, 2),
                    'discount' => number_format($item->discount, 2),
                    'tax_rate' => number_format($item->tax_rate, 2),
                    'vat_amount' => number_format($item->vat_amount, 2),
                    'total' => number_format($item->total, 2),
                ];
            })
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $salesReturn = SalesReturn::findOrFail($id);
        $salesReturn->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
        ]);
    }
}
