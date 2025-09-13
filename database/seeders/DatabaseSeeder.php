<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => 'admin',
        ]);
        User::create([
            'name' => 'kasir',
            'email' => 'kasir@gmail.com',
            'role' => 'kasir',
            'password' => 'kasir',
        ]);
        User::create([
            'name' => 'pimpinan',
            'email' => 'pimpinan@gmail.com',
            'role' => 'pimpinan',
            'password' => 'pimpinan',
        ]);
    }
}
