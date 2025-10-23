<?php

namespace App\Services;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rules\In;

interface RequestServiceInterface
{
    public function getAllRequests(): Collection;
    public function createRequest(array $data): Request;
    public function getRequestById(string $id): ?Request;
    public function updateRequest(Request $request, array $data): bool;
    public function deleteRequest(Request $request): ?bool;
    public function getRequestByUserId(string $userId);
}
