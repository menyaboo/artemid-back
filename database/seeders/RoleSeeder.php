<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(['code' => 'client'], ['name' => 'Клиент']);
        Role::updateOrCreate(['code' => 'admin'], ['name' => 'Админ']);
        Role::updateOrCreate(['code' => 'manager'], ['name' => 'Менеджер']);
    }
}
