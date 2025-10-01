<?php

namespace App\Providers;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class RequestService implements RequestServiceInterface
{
    public function getAllRequests(): Collection
    {
        return Request::all();
    }

    public function createRequest(array $data): Request
    {
        return Request::create([
            'id' => Str::uuid(),
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'queue' => $data['queue'],
            'request' => $data['request'],
        ]);
    }

    public function getRequestById(string $id): ?Request
    {
        return Request::find($id);
    }

    public function updateRequest(Request $request, array $data): bool
    {
        return $request->update($data);
    }

    public function deleteRequest(Request $request): ?bool
    {
        return $request->delete();
    }
}
