<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceItemSeeder extends Seeder
{
    public function run(): void
    {
        $invoiceItems = [
            ['invoice_id' => 1, 'item_id' => 1, 'quantity' => 1, 'unit_price' => 1299.99, 'taxable_price' => 1299.99, 'discount' => 0.00, 'tax_rate' => 10.00, 'total' => 1429.99],
            ['invoice_id' => 1, 'item_id' => 2, 'quantity' => 1, 'unit_price' => 29.99, 'taxable_price' => 29.99, 'discount' => 0.00, 'tax_rate' => 10.00, 'total' => 32.99],
            ['invoice_id' => 2, 'item_id' => 4, 'quantity' => 1, 'unit_price' => 199.99, 'taxable_price' => 199.99, 'discount' => 24.50, 'tax_rate' => 10.00, 'total' => 192.99],
            ['invoice_id' => 2, 'item_id' => 5, 'quantity' => 1, 'unit_price' => 89.99, 'taxable_price' => 89.99, 'discount' => 0.00, 'tax_rate' => 10.00, 'total' => 98.99],
            ['invoice_id' => 3, 'item_id' => 6, 'quantity' => 3, 'unit_price' => 59.99, 'taxable_price' => 179.97, 'discount' => 0.00, 'tax_rate' => 10.00, 'total' => 197.97],
            ['invoice_id' => 4, 'item_id' => 7, 'quantity' => 2, 'unit_price' => 49.99, 'taxable_price' => 99.98, 'discount' => 8.99, 'tax_rate' => 10.00, 'total' => 100.09],
            ['invoice_id' => 5, 'item_id' => 3, 'quantity' => 5, 'unit_price' => 12.99, 'taxable_price' => 64.95, 'discount' => 0.00, 'tax_rate' => 10.00, 'total' => 71.45],
            ['invoice_id' => 5, 'item_id' => 8, 'quantity' => 1, 'unit_price' => 34.99, 'taxable_price' => 34.99, 'discount' => 0.00, 'tax_rate' => 10.00, 'total' => 38.49],
        ];

        DB::table('invoice_items')->insert($invoiceItems);
    }
}
