<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        User::create([
            'id' => Str::uuid(),
            'username' => 'admin',
            'nim' => '1234567890123',
            'password' => Hash::make('password'),
            'full_name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '081234567890',
            'role' => 'admin',
        ]);

        // Create Regular User
        User::create([
            'id' => Str::uuid(),
            'username' => 'user',
            'nim' => '1234567890124',
            'password' => Hash::make('password'),
            'full_name' => 'Regular User',
            'email' => 'user@example.com',
            'phone' => '081234567891',
            'role' => 'user',
        ]);
    }
}