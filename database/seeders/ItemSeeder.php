<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Laptop Dell XPS 15', 'sku' => 'LAP-001', 'stock' => 50, 'price' => 1299.99],
            ['name' => 'Wireless Mouse', 'sku' => 'MOU-001', 'stock' => 200, 'price' => 29.99],
            ['name' => 'USB-C Cable', 'sku' => 'USB-001', 'stock' => 500, 'price' => 12.99],
            ['name' => 'Monitor 24 inch', 'sku' => 'MON-001', 'stock' => 75, 'price' => 199.99],
            ['name' => 'Keyboard Mechanical', 'sku' => 'KEY-001', 'stock' => 100, 'price' => 89.99],
            ['name' => 'Headphones Bluetooth', 'sku' => 'HEA-001', 'stock' => 150, 'price' => 59.99],
            ['name' => 'Webcam HD', 'sku' => 'WEB-001', 'stock' => 80, 'price' => 49.99],
            ['name' => 'Laptop Bag', 'sku' => 'BAG-001', 'stock' => 120, 'price' => 34.99],
        ];

        DB::table('items')->insert($items);
    }
}
