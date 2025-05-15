<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryService;

class CategoryServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoryService::updateOrCreate(['name' => 'Комплектующие ПК']);
        CategoryService::updateOrCreate(['name' => 'Бытовая техника']);
    }
}
