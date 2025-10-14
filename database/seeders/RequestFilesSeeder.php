<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\RequestFile as RequestFileModel;
use App\Models\Request as RequestModel;

class RequestFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requests = RequestModel::take(3)->get();

        if ($requests->isEmpty()) {
            $this->command->warn('No requests found, skipping RequestFilesSeeder');
            return;
        }

        foreach ($requests as $i => $req) {
            RequestFileModel::create([
                'id' => Str::uuid(),
                'request_id' => $req->id,
                'url' => "https://example.com/request_files/{$i}.wav",
            ]);
        }
    }
}
