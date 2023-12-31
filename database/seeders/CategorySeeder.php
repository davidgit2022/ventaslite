<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'CURSOS',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
        ]);

        Category::create([
            'name' => 'TENNIS',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
        ]);

        Category::create([
            'name' => 'CELULARES',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
        ]);

        Category::create([
            'name' => 'COMPUTADORAS',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
        ]);
    }
}
