<?php

namespace App\Providers;

use App\Models\RequestFile;
use Illuminate\Database\Eloquent\Collection;

interface RequestFileServiceInterface
{
    public function getAllRequestFiles(): Collection;
    public function createRequestFile(array $data): RequestFile;
    public function getRequestFileById(string $id): ?RequestFile;
    public function updateRequestFile(RequestFile $requestFile, array $data): bool;
    public function deleteRequestFile(RequestFile $requestFile): ?bool;
}
