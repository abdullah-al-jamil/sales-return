<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = [
            [
                'customer_name' => 'John Smith',
                'invoice_date' => '2026-03-01 10:30:00',
                'taxable_amount' => 1329.98,
                'discount_amount' => 0.00,
                'vat_amount' => 132.99,
                'total_amount' => 1462.97,
                'status' => 'paid',
            ],
            [
                'customer_name' => 'Jane Doe',
                'invoice_date' => '2026-03-02 14:15:00',
                'taxable_amount' => 244.98,
                'discount_amount' => 24.50,
                'vat_amount' => 22.05,
                'total_amount' => 242.53,
                'status' => 'paid',
            ],
            [
                'customer_name' => 'Bob Johnson',
                'invoice_date' => '2026-03-03 09:00:00',
                'taxable_amount' => 179.98,
                'discount_amount' => 0.00,
                'vat_amount' => 18.00,
                'total_amount' => 197.98,
                'status' => 'unpaid',
            ],
            [
                'customer_name' => 'Alice Brown',
                'invoice_date' => '2026-03-04 16:45:00',
                'taxable_amount' => 89.99,
                'discount_amount' => 8.99,
                'vat_amount' => 8.10,
                'total_amount' => 89.10,
                'status' => 'paid',
            ],
            [
                'customer_name' => 'Charlie Wilson',
                'invoice_date' => '2026-03-05 11:20:00',
                'taxable_amount' => 109.98,
                'discount_amount' => 0.00,
                'vat_amount' => 11.00,
                'total_amount' => 120.98,
                'status' => 'unpaid',
            ],
        ];

        DB::table('invoices')->insert($invoices);
    }
}
