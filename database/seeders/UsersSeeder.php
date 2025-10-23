<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        $users = [
            [
                'id' => Str::uuid(),
                'username' => 'admin',
                'nim' => '1234567890123',
                'password' => bcrypt('password'),
                'full_name' => 'Admin User',
                'email' => 'admin@example.com',
                'phone' => '081234567890',
                'role' => 'admin',
                'url_photo' => 'https://ui-avatars.com/api/?name=Admin+User&background=random',
            ],
            [
                'id' => Str::uuid(),
                'username' => 'user1',
                'nim' => '2234567890123',
                'password' => bcrypt('password'),
                'full_name' => 'Regular User',
                'email' => 'user1@example.com',
                'phone' => '082234567890',
                'role' => 'user',
                'url_photo' => 'https://ui-avatars.com/api/?name=Regular+User&background=random',
            ],
            [
                'id' => Str::uuid(),
                'username' => 'user2',
                'nim' => '3234567890123',
                'password' => bcrypt('password'),
                'full_name' => 'Another User',
                'email' => 'user2@example.com',
                'phone' => '083234567890',
                'role' => 'user',
                'url_photo' => 'https://ui-avatars.com/api/?name=Another+User&background=random',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                [
                    'username' => $userData['username'],
                    'nim' => $userData['nim']
                ],  // check if exists by username and nim
                $userData  // data to create if not exists
            );
        }
    }
}