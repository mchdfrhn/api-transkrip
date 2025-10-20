<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use App\Services\RequestServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct(private RequestServiceInterface $requestService)
    {
    }

    private function isAdmin(): bool
    {
        $user = Auth::user();
        return $user && ($user->role ?? null) === 'admin';
    }

    private function authorizeOwnerOrAdmin(RequestModel $requestModel)
    {
        if ($this->isAdmin()) {
            return;
        }

        if (Auth::id() !== $requestModel->user_id) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // hanya admin yang boleh melihat daftar semua request
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        return response()->json($this->requestService->getAllRequests());
    }

    public function myRequest()
    {
        $userId = Auth::id();

        // dd("user id dari token tersebut: ", $userId);
        //  dd("Tipe: ", gettype($userId));

        if (!$userId) {
            return response()->json([
                "status" => "failed",
                "message" => "user dengan id tersebut tidak ditemukan"
            ]);

            abort(401, 'Unauthenticated');
        }

        $request = $this->requestService->getRequestByUserId($userId);
        return response()->json([
            "status" => "success",
            "data" => $request
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'request' => 'required|string',
        ]);

        // jika bukan admin, user hanya boleh membuat request untuk dirinya sendiri
        if (! $this->isAdmin() && $data['user_id'] !== Auth::id()) {
            abort(403, 'Unauthorized to create request for another user');
        }

        $requestModel = $this->requestService->createRequest($data);

        return response()->json($requestModel, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestModel $request)
    {
        // admin atau pemilik request dapat melihat
        $this->authorizeOwnerOrAdmin($request);

        return response()->json($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $httpRequest, RequestModel $request)
    {
        // hanya admin yang boleh mengupdate
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        $data = $httpRequest->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'type' => 'sometimes|required|string',
            'request' => 'sometimes|required|string',
        ]);

        $this->requestService->updateRequest($request, $data);

        return response()->json($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestModel $request)
    {
        // hanya admin yang boleh menghapus
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        $this->requestService->deleteRequest($request);

        return response()->json(null, 204);
    }
}
