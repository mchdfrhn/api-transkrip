<?php

namespace Database\Seeders;

use App\Models\Request;
use App\Models\Response;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResponsesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requests = Request::all();
        
        if ($requests->isEmpty()) {
            $this->command->warn('No requests found, skipping ResponsesSeeder');
            return;
        }

        foreach ($requests as $index => $request) {
            Response::firstOrCreate(
                ['request_id' => $request->id],
                [
                    'id' => Str::uuid(),
                    'request_id' => $request->id,
                    'response' => "Hasil transkrip untuk permintaan #".($index + 1),
                ]
            );
        }
    }
}
