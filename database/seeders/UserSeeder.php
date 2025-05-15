<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Администратор',
                'email' => 'admin@example.com',
                'role_id' => 2,
                'password' => Hash::make('123456'),
                'telephone' => '79001234567',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Менеджер',
                'email' => 'manager@example.com',
                'role_id' => 3,
                'password' => Hash::make('123456'),
                'telephone' => '79007654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Пользователь',
                'email' => 'user@example.com',
                'role_id' => 1,
                'password' => Hash::make('123456'),
                'telephone' => '79000000000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
