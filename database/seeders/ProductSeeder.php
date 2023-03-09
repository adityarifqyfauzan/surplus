<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Nasi Goreng',
                'description' => 'Nasi Goreng Sambal Ijo Pedas'
            ],
            [
                'name' => 'Es Kelapa Tua',
                'description' => 'Kelapa Asli'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $products = Product::all();

        foreach ($products as $product) {


            switch ($product->name) {
                case 'Nasi Goreng':
                    $categories = Category::whereIn('name', ['Makanan', 'Pedas'])->get();
                    foreach ($categories as $category) {
                        ProductCategory::create(['product_id' => $product->id, 'category_id' => $category->id]);
                    }
                    break;

                case 'Es Kelapa Tua':
                    $category = Category::where('name', 'Minuman')->first();
                    ProductCategory::create(['product_id' => $product->id, 'category_id' => $category->id]);
                default:
                    # code...
                    break;
            }
        }
    }
}
