<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::factory(15)->create();

        Product::factory(12)->create();
    }
}
