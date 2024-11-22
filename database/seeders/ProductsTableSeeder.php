<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $now = now();
        // DB::table('products')->insert([
        //     'name' => 'Product A',
        //     'price' => 100,
        //     'stock' => 50,
        //     'created_at' => $now,
        //     'updated_at' => $now,
        // ]);

        // DB::table('products')->insert([
        //     'name' => 'Product B',
        //     'price' => 200,
        //     'stock' => 30,
        //     'created_at' => $now,
        //     'updated_at' => $now,
        // ]);

        $products = Product::factory()->count(300)->make();

        $products->chunk(50)->each(function ($chunk) {
            foreach ($chunk as $product) {
                $product->save();
            }
        });
    }
}
