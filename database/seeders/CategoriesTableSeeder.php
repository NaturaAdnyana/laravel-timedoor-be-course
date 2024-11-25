<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::factory()->count(300)->make();

        $categories->chunk(6)->each(function ($chunk) {
            foreach ($chunk as $category) {
                $category->save();
            }
        });
    }
}
