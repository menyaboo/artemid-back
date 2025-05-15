<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CategoryServiceSeeder::class,
            StatusServiceSeeder::class,
            TypeServiceSeeder::class,
            UserSeeder::class
        ]);
    }
}
