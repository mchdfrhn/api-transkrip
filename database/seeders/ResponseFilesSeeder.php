<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ResponseFile as ResponseFileModel;
use App\Models\Response as ResponseModel;

class ResponseFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $responses = ResponseModel::take(3)->get();

        if ($responses->isEmpty()) {
            $this->command->warn('No responses found, skipping ResponseFilesSeeder');
            return;
        }

        foreach ($responses as $i => $res) {
            ResponseFileModel::create([
                'id' => Str::uuid(),
                'response_id' => $res->id,
                'url' => "https://example.com/response_files/{$i}.zip",
            ]);
        }
    }
}
