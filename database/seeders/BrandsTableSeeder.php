<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::factory()->count(300)->make();

        $brands->chunk(6)->each(function ($chunk) {
            foreach ($chunk as $brand) {
                $brand->save();
            }
        });
    }
}
