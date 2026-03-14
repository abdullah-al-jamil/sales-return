<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesReturnItemSeeder extends Seeder
{
    public function run(): void
    {
        $returnItems = [
            [
                'sales_return_id' => 1,
                'invoice_item_id' => 2,
                'item_id' => 2,
                'quantity' => 1,
                'unit_price' => 29.99,
                'taxable_price' => 29.99,
                'discount' => 0.00,
                'tax_rate' => 10.00,
                'vat_amount' => 3.00,
                'total' => 32.99,
            ],
            [
                'sales_return_id' => 2,
                'invoice_item_id' => 4,
                'item_id' => 5,
                'quantity' => 1,
                'unit_price' => 89.99,
                'taxable_price' => 89.99,
                'discount' => 0.00,
                'tax_rate' => 10.00,
                'vat_amount' => 9.00,
                'total' => 98.99,
            ],
        ];

        DB::table('sales_return_items')->insert($returnItems);
    }
}
