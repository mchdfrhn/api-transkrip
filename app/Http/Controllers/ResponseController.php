<?php

namespace App\Http\Controllers;

use App\Models\Response as ResponseModel;
use App\Providers\ResponseServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function __construct(private ResponseServiceInterface $responseService)
    {
    }

    private function isAdmin(): bool
    {
        $user = Auth::user();
        return $user && ($user->role ?? null) === 'admin';
    }

    private function authorizeOwnerOrAdmin(ResponseModel $response)
    {
        if ($this->isAdmin()) {
            return;
        }

        // pastikan relation request tersedia; jika tidak, eager load
        if (! $response->relationLoaded('request')) {
            $response->load('request');
        }

        $requestModel = $response->request;

        if (! $requestModel || Auth::id() !== $requestModel->user_id) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // hanya admin boleh melihat semua response
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        return response()->json($this->responseService->getAllResponses());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // hanya admin boleh membuat response
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        $data = $request->validate([
            'request_id' => 'required|exists:requests,id',
            'response' => 'required|string',
            'status' => 'sometimes|in:pending,in_progress,completed',
        ]);

        $responseModel = $this->responseService->createResponse($data);

        return response()->json($responseModel, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ResponseModel $response)
    {
        // admin atau owner dari request terkait dapat melihat
        $this->authorizeOwnerOrAdmin($response);

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResponseModel $response)
    {
        // hanya admin boleh mengupdate
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        $data = $request->validate([
            'request_id' => 'sometimes|required|exists:requests,id',
            'response' => 'sometimes|required|string',
            'status' => 'sometimes|in:pending,in_progress,completed',
        ]);

        $this->responseService->updateResponse($response, $data);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResponseModel $response)
    {
        // hanya admin boleh menghapus
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        $this->responseService->deleteResponse($response);

        return response()->json(null, 204);
    }
}
