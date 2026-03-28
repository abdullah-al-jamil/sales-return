<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesReturnTest extends TestCase
{
    use RefreshDatabase;

    protected function createItem($overrides = [])
    {
        return Item::create(array_merge([
            'name' => 'Test Item',
            'sku' => 'SKU-001',
            'stock' => 0,
            'price' => 100.00,
        ], $overrides));
    }

    protected function createInvoice($overrides = [])
    {
        return Invoice::create(array_merge([
            'customer_name' => 'Test Customer',
            'invoice_date' => now(),
            'taxable_amount' => 1000.00,
            'discount_amount' => 50.00,
            'vat_amount' => 150.00,
            'total_amount' => 1100.00,
            'status' => 'paid',
        ], $overrides));
    }

    protected function createInvoiceItem($invoice, $item, $overrides = [])
    {
        return InvoiceItem::create(array_merge([
            'invoice_id' => $invoice->id,
            'item_id' => $item->id,
            'quantity' => 10,
            'unit_price' => 100.00,
            'taxable_price' => 100.00,
            'discount' => 5.00,
            'tax_rate' => 15.00,
            'total' => 1150.00,
        ], $overrides));
    }

    protected function createStock($item)
    {
        return Stock::create([
            'item_id' => $item->id,
            'stock_in' => 0,
            'stock_out' => 0,
            'available_stock' => 0,
        ]);
    }

    public function test_return_partial_items()
    {
        $item1 = $this->createItem(['name' => 'Item 1', 'sku' => 'SKU-001', 'stock' => 100]);
        $item2 = $this->createItem(['name' => 'Item 2', 'sku' => 'SKU-002', 'stock' => 100]);
        $this->createStock($item1);
        $this->createStock($item2);

        $invoice = $this->createInvoice();

        $invoiceItem1 = $this->createInvoiceItem($invoice, $item1, [
            'quantity' => 10,
            'unit_price' => 100.00,
            'discount' => 5.00,
            'tax_rate' => 15.00,
            'total' => 1092.50,
        ]);

        $invoiceItem2 = $this->createInvoiceItem($invoice, $item2, [
            'quantity' => 10,
            'unit_price' => 100.00,
            'discount' => 5.00,
            'tax_rate' => 15.00,
            'total' => 1092.50,
        ]);

        $returnData = [
            'return_date' => now()->toDateTimeString(),
            'reason' => 'Partial return test',
            'refund_method' => 'Cash',
            'items' => [
                [
                    'selected' => true,
                    'invoice_item_id' => $invoiceItem1->id,
                    'item_id' => $item1->id,
                    'quantity' => 5,
                ],
            ],
        ];

        $response = $this->postJson("/invoices/{$invoice->id}/return", $returnData);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('sales_returns', [
            'invoice_id' => $invoice->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('sales_return_items', [
            'invoice_item_id' => $invoiceItem1->id,
            'quantity' => 5,
        ]);

        $item1->refresh();
        $this->assertEquals(105, $item1->stock);

        $item2->refresh();
        $this->assertEquals(100, $item2->stock);

        $invoice->refresh();
        $this->assertEquals('returned', $invoice->status);
    }

    public function test_return_all_items()
    {
        $item1 = $this->createItem(['name' => 'Item 1', 'sku' => 'SKU-001', 'stock' => 50]);
        $item2 = $this->createItem(['name' => 'Item 2', 'sku' => 'SKU-002', 'stock' => 50]);
        $this->createStock($item1);
        $this->createStock($item2);

        $invoice = $this->createInvoice();

        $invoiceItem1 = $this->createInvoiceItem($invoice, $item1, [
            'quantity' => 10,
            'unit_price' => 100.00,
            'discount' => 5.00,
            'tax_rate' => 15.00,
            'total' => 1092.50,
        ]);

        $invoiceItem2 = $this->createInvoiceItem($invoice, $item2, [
            'quantity' => 10,
            'unit_price' => 100.00,
            'discount' => 5.00,
            'tax_rate' => 15.00,
            'total' => 1092.50,
        ]);

        $returnData = [
            'return_date' => now()->toDateTimeString(),
            'reason' => 'Full return test',
            'refund_method' => 'Bank',
            'items' => [
                [
                    'selected' => true,
                    'invoice_item_id' => $invoiceItem1->id,
                    'item_id' => $item1->id,
                    'quantity' => 10,
                ],
                [
                    'selected' => true,
                    'invoice_item_id' => $invoiceItem2->id,
                    'item_id' => $item2->id,
                    'quantity' => 10,
                ],
            ],
        ];

        $response = $this->postJson("/invoices/{$invoice->id}/return", $returnData);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('sales_returns', [
            'invoice_id' => $invoice->id,
            'status' => 'approved',
        ]);

        $item1->refresh();
        $item2->refresh();
        $this->assertEquals(60, $item1->stock);
        $this->assertEquals(60, $item2->stock);

        $invoice->refresh();
        $this->assertEquals('returned', $invoice->status);
    }

    public function test_return_over_sold_quantity_validation()
    {
        $item = $this->createItem(['name' => 'Test Item', 'sku' => 'SKU-001', 'stock' => 100]);
        $this->createStock($item);

        $invoice = $this->createInvoice();

        $invoiceItem = $this->createInvoiceItem($invoice, $item, [
            'quantity' => 10,
            'unit_price' => 100.00,
            'discount' => 5.00,
            'tax_rate' => 15.00,
            'total' => 1092.50,
        ]);

        $returnData = [
            'return_date' => now()->toDateTimeString(),
            'reason' => 'Over return test',
            'refund_method' => 'Cash',
            'items' => [
                [
                    'selected' => true,
                    'invoice_item_id' => $invoiceItem->id,
                    'item_id' => $item->id,
                    'quantity' => 15,
                ],
            ],
        ];

        $response = $this->postJson("/invoices/{$invoice->id}/return", $returnData);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'message' => 'Return quantity cannot exceed sold quantity. Maximum returnable: 10',
        ]);

        $this->assertStringContainsString('cannot exceed sold quantity', $response->json('message'));
        $this->assertStringContainsString('Maximum returnable: 10', $response->json('message'));

        $this->assertDatabaseMissing('sales_returns', [
            'invoice_id' => $invoice->id,
        ]);

        $item->refresh();
        $this->assertEquals(100, $item->stock);
    }

    public function test_return_over_remaining_after_partial_validation()
    {
        $item = $this->createItem(['name' => 'Test Item', 'sku' => 'SKU-001', 'stock' => 100]);
        $this->createStock($item);

        $invoice = $this->createInvoice();

        $invoiceItem = $this->createInvoiceItem($invoice, $item, [
            'quantity' => 10,
            'unit_price' => 100.00,
            'discount' => 5.00,
            'tax_rate' => 15.00,
            'total' => 1092.50,
        ]);

        $salesReturn = SalesReturn::create([
            'invoice_id' => $invoice->id,
            'customer_name' => $invoice->customer_name,
            'return_date' => now(),
            'taxable_amount' => 500.00,
            'discount_amount' => 25.00,
            'vat_amount' => 75.00,
            'total_amount' => 550.00,
            'reason' => 'First partial return',
            'status' => 'approved',
            'refund_method' => 'Cash',
        ]);

        SalesReturnItem::create([
            'sales_return_id' => $salesReturn->id,
            'invoice_item_id' => $invoiceItem->id,
            'item_id' => $item->id,
            'quantity' => 5,
            'unit_price' => 100.00,
            'taxable_price' => 95.00,
            'discount' => 5.00,
            'tax_rate' => 15.00,
            'vat_amount' => 14.25,
            'total' => 109.25,
        ]);

        $item->stock += 5;
        $item->save();

        $returnData = [
            'return_date' => now()->toDateTimeString(),
            'reason' => 'Second over return test',
            'refund_method' => 'Cash',
            'items' => [
                [
                    'selected' => true,
                    'invoice_item_id' => $invoiceItem->id,
                    'item_id' => $item->id,
                    'quantity' => 8,
                ],
            ],
        ];

        $response = $this->postJson("/invoices/{$invoice->id}/return", $returnData);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'message' => 'Return quantity cannot exceed sold quantity. Maximum returnable: 5',
        ]);

        $this->assertStringContainsString('cannot exceed sold quantity', $response->json('message'));
        $this->assertStringContainsString('Maximum returnable: 5', $response->json('message'));

        $salesReturnCount = SalesReturn::where('invoice_id', $invoice->id)->count();
        $this->assertEquals(1, $salesReturnCount);
    }
}
