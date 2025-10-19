<?php

namespace App\Services;

use App\Models\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ResponseService implements ResponseServiceInterface
{
    public function getAllResponses(): Collection
    {
        return Response::all();
    }

    public function createResponse(array $data): Response
    {
        return Response::create([
            'id' => Str::uuid(),
            'request_id' => $data['request_id'],
            'response' => $data['response'],
            'status' => $data['status'] ?? 'pending',
        ]);
    }

    public function getResponseById(string $id): ?Response
    {
        return Response::find($id);
    }

    public function updateResponse(Response $response, array $data): bool
    {
        return $response->update($data);
    }

    public function deleteResponse(Response $response): ?bool
    {
        return $response->delete();
    }
}
