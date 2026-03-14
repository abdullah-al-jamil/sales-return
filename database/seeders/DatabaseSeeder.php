<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            ItemSeeder::class,
            InvoiceSeeder::class,
            InvoiceItemSeeder::class,
            StockTransactionSeeder::class,
            SalesReturnSeeder::class,
            SalesReturnItemSeeder::class,
        ]);
    }
}
