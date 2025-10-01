<?php

namespace App\Providers;

use App\Models\ResponseFile;
use Illuminate\Database\Eloquent\Collection;

interface ResponseFileServiceInterface
{
    public function getAllResponseFiles(): Collection;
    public function createResponseFile(array $data): ResponseFile;
    public function getResponseFileById(string $id): ?ResponseFile;
    public function updateResponseFile(ResponseFile $responseFile, array $data): bool;
    public function deleteResponseFile(ResponseFile $responseFile): ?bool;
}
