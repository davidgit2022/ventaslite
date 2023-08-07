<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'LARAVEL Y LIVEWIRE',
            'barcode' => '750100',
            'cost' => 200,
            'price' => 350,
            'stock' => 1000,
            'alerts' => 10,
            'image' => 'curso.png',
            'category_id' =>1,
        ]);
        Product::create([
            'name' => 'RUNNING NIKE',
            'barcode' => '750185',
            'cost' => 600,
            'price' => 1500,
            'stock' => 1000,
            'alerts' => 10,
            'image' => 'tenis.png',
            'category_id' => 2,
        ]);
        Product::create([
            'name' => 'IPHONE 11',
            'barcode' => '7865265',
            'cost' => 900,
            'price' => 1400,
            'stock' => 1000,
            'alerts' => 10,
            'image' => 'iphone11.png',
            'category_id' => 3,
        ]);
        Product::create([
            'name' => 'PC GAMMER',
            'barcode' => '785888',
            'cost' => 790,
            'price' => 1300,
            'stock' => 1000,
            'alerts' => 10,
            'image' => 'pcgammer.png',
            'category_id' => 4
        ]);
    }
}
