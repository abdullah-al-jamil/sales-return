<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $items = Item::all();

        foreach ($items as $item) {
            Stock::create([
                'item_id' => $item->id,
                'opening_stock' => $item->stock,
                'stock_in' => 0,
                'stock_out' => 0,
                'available_stock' => $item->stock,
            ]);
        }
    }
}
