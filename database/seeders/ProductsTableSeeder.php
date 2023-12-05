<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [];

        for ($i = 1; $i <= 100; $i++) {
            $products[] = [
                'name' => 'Product ' . $i,
                'price' => 0.99 + ($i * 2),
                'description' => 'Description for Product ' . $i,
            ];
        }

        DB::table('products')->insert($products);
    }
}
