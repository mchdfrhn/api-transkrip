<?php

namespace App\Providers;

use App\Models\ResponseFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ResponseFileService implements ResponseFileServiceInterface
{
    public function getAllResponseFiles(): Collection
    {
        return ResponseFile::all();
    }

    public function createResponseFile(array $data): ResponseFile
    {
        return ResponseFile::create([
            'id' => Str::uuid(),
            'response_id' => $data['response_id'],
            'url' => $data['url'],
        ]);
    }

    public function getResponseFileById(string $id): ?ResponseFile
    {
        return ResponseFile::find($id);
    }

    public function updateResponseFile(ResponseFile $responseFile, array $data): bool
    {
        return $responseFile->update($data);
    }

    public function deleteResponseFile(ResponseFile $responseFile): ?bool
    {
        return $responseFile->delete();
    }
}
