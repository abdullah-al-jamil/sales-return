<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $transactions = [
            ['item_id' => 1, 'type' => 'sale', 'reference_id' => 1, 'quantity' => 1, 'stock_effect' => -1],
            ['item_id' => 2, 'type' => 'sale', 'reference_id' => 1, 'quantity' => 1, 'stock_effect' => -1],
            ['item_id' => 4, 'type' => 'sale', 'reference_id' => 2, 'quantity' => 1, 'stock_effect' => -1],
            ['item_id' => 5, 'type' => 'sale', 'reference_id' => 2, 'quantity' => 1, 'stock_effect' => -1],
            ['item_id' => 6, 'type' => 'sale', 'reference_id' => 3, 'quantity' => 3, 'stock_effect' => -3],
            ['item_id' => 7, 'type' => 'sale', 'reference_id' => 4, 'quantity' => 2, 'stock_effect' => -2],
            ['item_id' => 3, 'type' => 'sale', 'reference_id' => 5, 'quantity' => 5, 'stock_effect' => -5],
            ['item_id' => 8, 'type' => 'sale', 'reference_id' => 5, 'quantity' => 1, 'stock_effect' => -1],
        ];

        DB::table('stock_transactions')->insert($transactions);
    }
}
