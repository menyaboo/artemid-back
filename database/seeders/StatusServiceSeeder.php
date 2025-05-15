<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StatusService;

class StatusServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusService::updateOrCreate(['code' => 'open'], ['name' => 'На рассмотрении']);
        StatusService::updateOrCreate(['code' => 'working'], ['name' => 'В работе']);
        StatusService::updateOrCreate(['code' => 'done'], ['name' => 'Завершено']);
        StatusService::updateOrCreate(['code' => 'cancel'], ['name' => 'Отменен']);
    }
}
