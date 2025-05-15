<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeService;

class TypeServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeService::updateOrCreate(['category_id' => 1], ['name' => 'Не выдает изображение']);
        TypeService::updateOrCreate(['category_id' => 1], ['name' => 'Перегреваетя']);
        TypeService::updateOrCreate(['category_id' => 2], ['name' => 'Залило']);
        TypeService::updateOrCreate(['category_id' => 2], ['name' => 'Сгорело']);
    }
}
