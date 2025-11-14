<?php

namespace App\Services;

use App\Models\Request;
use App\Models\Response;
use App\Helpers\QueueHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RequestService implements RequestServiceInterface
{
    public function getAllRequests(): Collection
    {
        return Request::all();
    }

    public function getRequestsByUserId(string $userId): Collection
    {
        return Request::where('user_id', $userId)
            ->with(['user', 'response', 'response.responseFiles', 'requestFiles'])
            ->get();
    }

    public function createRequest(array $data): Request
    {
        return DB::transaction(function () use ($data) {
            // Generate queue number based on request type
            $queueNumber = QueueHelper::generateQueueNumber($data['type']);

            $request = Request::create([
                'id' => Str::uuid(),
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'queue' => $queueNumber,
                'request' => $data['request'],
                'status' => $data['status'] ?? 'pending',
            ]);

            $request->response()->create([
                'id' => Str::uuid(),
                'response' => ''
            ]);

            return $request;
        });
    }

    public function getRequestById(string $id): ?Request
    {
        try {
            return Request::where('id', $id)
                ->with(['user', 'response', 'requestFiles'])
                ->first();
        } catch (\Exception $e) {
            Log::error('Error in getRequestById: ' . $e->getMessage());
            return null;
        }
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
