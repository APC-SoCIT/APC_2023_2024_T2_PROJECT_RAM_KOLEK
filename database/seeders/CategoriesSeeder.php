<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'name' => 'Hospitality',
        ]);
        DB::table('categories')->insert([
            'name' => 'Food Service',
        ]);
        DB::table('categories')->insert([
            'name' => 'Retail/Wholesale',
        ]);
        DB::table('categories')->insert([
            'name' => 'Medical',
        ]);
        DB::table('categories')->insert([
            'name' => 'E-Commerce',
        ]);
        DB::table('categories')->insert([
            'name' => 'Agriculture',
        ]);
        DB::table('categories')->insert([
            'name' => 'Government/LGU',
        ]);
        DB::table('categories')->insert([
            'name' => 'Human Resource',
        ]);
        DB::table('categories')->insert([
            'name' => 'Marketing and Distribution',
        ]);
        DB::table('categories')->insert([
            'name' => 'Manufacturing',
        ]);

    }
}