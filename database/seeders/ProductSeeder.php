<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create(['title' => '測試資料1', 'content' => '測試內容', 'price' => rand(0, 300), 'quantity' => 20]);
        Product::create(['title' => '測試資料2', 'content' => '測試內容', 'price' => rand(0, 300), 'quantity' => 20]);
        Product::create(['title' => '測試資料3', 'content' => '測試內容', 'price' => rand(0, 300), 'quantity' => 20]);
    }
}
