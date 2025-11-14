<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Request as RequestModel;
use App\Services\RequestServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        // Admin dapat mengakses semua request
        if ($this->isAdmin()) {
            return;
        }

        // User hanya dapat mengakses request miliknya
        if ($user->id !== $requestModel->user_id) {
            abort(403, 'Unauthorized: Anda tidak memiliki akses ke request ini');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // hanya admin yang boleh melihat daftar semua request
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        return ApiResponse::success($this->requestService->getAllRequests(), 'Successfully retrieved all requests');
    }

    /**
     * Display a listing of requests for a specific user.
     */
    public function getUserRequests($id_user)
    {
        try {
            $user = Auth::user();
            
            // User hanya bisa lihat request milik mereka sendiri, kecuali admin
            if (!$this->isAdmin() && $user->id !== $id_user) {
                abort(403, 'Unauthorized: Anda tidak dapat melihat request user lain');
            }

            $requests = $this->requestService->getRequestsByUserId($id_user);

            return ApiResponse::success(
                $requests,
                'Successfully retrieved user requests',
                200
            );
        } catch (\Exception $e) {
            Log::error('Error fetching user requests: ' . $e->getMessage());
            return ApiResponse::error(
                'Terjadi kesalahan saat mengambil data request',
                500,
                ['error' => $e->getMessage()]
            );
        }
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
            'status' => 'sometimes|in:pending,in_progress,completed',
        ]);

        // jika bukan admin, user hanya boleh membuat request untuk dirinya sendiri
        if (! $this->isAdmin() && $data['user_id'] !== Auth::id()) {
            abort(403, 'Unauthorized to create request for another user');
        }

        $requestModel = $this->requestService->createRequest($data);

        return ApiResponse::created($requestModel, 'Request created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Coba dapatkan request dengan ID yang diberikan
            $request = $this->requestService->getRequestById($id);
            
            if (!$request) {
                return ApiResponse::notFound('Request tidak ditemukan');
            }
            
            // Cek otorisasi
            $this->authorizeOwnerOrAdmin($request);

            // Load semua relasi yang diperlukan
            $request->load([
                'user', 
                'response', 
                'response.responseFiles',
                'requestFiles'
            ]);

            return ApiResponse::success($request, 'Request details retrieved successfully');

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error fetching request: ' . $e->getMessage());
            
            return ApiResponse::error('Terjadi kesalahan saat mengambil data request', 500, ['error' => $e->getMessage()]);
        }
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
            'status' => 'sometimes|in:pending,in_progress,completed',
        ]);

        $this->requestService->updateRequest($request, $data);

        return ApiResponse::success($request, 'Request updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestModel $request)
    {
        // hanya admin yang boleh menghapus
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        $this->requestService->deleteRequest($request);

        return ApiResponse::success(null, 'Request deleted successfully', 204);
    }
}
