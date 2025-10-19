<?php

namespace Database\Seeders;

use App\Models\Request;
use App\Models\User;
use App\Helpers\QueueHelper;
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

        $requestsData = [
            [
                'user_id' => $users->firstWhere('role', 'user')->id,
                'type' => 'transkrip',
                'request' => 'Permintaan transkrip semester 1',
            ],
            [
                'user_id' => $users->firstWhere('username', 'user1')->id,
                'type' => 'transkrip',
                'request' => 'Permintaan transkrip semester 2',
            ],
            [
                'user_id' => $users->firstWhere('username', 'user2')->id,
                'type' => 'transkrip',
                'request' => 'Permintaan transkrip semester 3',
            ],
        ];

        $requests = [];
        foreach ($requestsData as $data) {
            $requests[] = [
                'id' => Str::uuid(),
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'queue' => QueueHelper::generateQueueNumber($data['type']),
                'request' => $data['request'],
            ];
        };

        foreach ($requests as $requestData) {
            Request::create($requestData);
        }
    }
}
