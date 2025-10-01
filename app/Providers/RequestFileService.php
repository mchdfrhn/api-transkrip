<?php

namespace App\Providers;

use App\Models\RequestFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class RequestFileService implements RequestFileServiceInterface
{
    public function getAllRequestFiles(): Collection
    {
        return RequestFile::all();
    }

    public function createRequestFile(array $data): RequestFile
    {
        return RequestFile::create([
            'id' => Str::uuid(),
            'request_id' => $data['request_id'],
            'url' => $data['url'],
        ]);
    }

    public function getRequestFileById(string $id): ?RequestFile
    {
        return RequestFile::find($id);
    }

    public function updateRequestFile(RequestFile $requestFile, array $data): bool
    {
        return $requestFile->update($data);
    }

    public function deleteRequestFile(RequestFile $requestFile): ?bool
    {
        return $requestFile->delete();
    }
}
