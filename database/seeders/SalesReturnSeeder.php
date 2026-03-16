<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesReturnSeeder extends Seeder
{
    public function run(): void
    {
        $returns = [
            [
                'invoice_id' => 1,
                'customer_name' => 'John Smith',
                'return_date' => '2026-03-05 15:00:00',
                'taxable_amount' => 29.99,
                'discount_amount' => 0.00,
                'vat_amount' => 3.00,
                'total_amount' => 32.99,
                'reason' => 'Wrong item received',
                'status' => 'approved',
                'refund_method' => 'Cash',
            ],
            [
                'invoice_id' => 2,
                'customer_name' => 'Jane Doe',
                'return_date' => '2026-03-06 10:30:00',
                'taxable_amount' => 89.99,
                'discount_amount' => 0.00,
                'vat_amount' => 9.00,
                'total_amount' => 98.99,
                'reason' => 'Product defective',
                'status' => 'pending',
                'refund_method' => 'Bank',
            ],
        ];

        DB::table('sales_returns')->insert($returns);
    }
}
