<?php

namespace Database\Seeders;

use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan ID user yang sudah ada
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Users not found, skipping RequestsSeeder');
            return;
        }

        $requests = [
            [
                'id' => Str::uuid(),
                'user_id' => $users->firstWhere('role', 'user')->id,
                'type' => 'transkrip',
                'queue' => 1,
                'request' => 'Permintaan transkrip semester 1',
            ],
            [
                'id' => Str::uuid(),
                'user_id' => $users->firstWhere('username', 'user1')->id,
                'type' => 'transkrip',
                'queue' => 2,
                'request' => 'Permintaan transkrip semester 2',
            ],
            [
                'id' => Str::uuid(),
                'user_id' => $users->firstWhere('username', 'user2')->id,
                'type' => 'transkrip',
                'queue' => 3,
                'request' => 'Permintaan transkrip semester 3',
            ],
        ];

        foreach ($requests as $requestData) {
            Request::firstOrCreate(
                [
                    'user_id' => $requestData['user_id'],
                    'queue' => $requestData['queue']
                ],
                $requestData
            );
        }
    }
}
