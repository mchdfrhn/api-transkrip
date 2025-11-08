<?php

namespace App\Http\Controllers;

use App\Models\Notification as NotifModel;
use App\Services\NotificationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotifController extends Controller
{
    public function __construct(private NotificationServiceInterface $notificationService)
    {
    }

    private function isAdmin(): bool
    {
        $user = Auth::user();
        return $user && ($user->role ?? null) === 'admin';
    }

    private function authorizeOwnerOrAdmin(NotifModel $notifModel)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        // Admin dapat mengakses semua notifikasi
        if ($this->isAdmin()) {
            return;
        }

        // User hanya dapat mengakses notifikasi miliknya
        if ($user->id !== $notifModel->user_id) {
            abort(403, 'Unauthorized: Anda tidak memiliki akses ke notifikasi ini');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // hanya admin yang boleh melihat daftar semua notifikasi
        abort_unless($this->isAdmin(), 403, 'Unauthorized');

        return response()->json($this->notificationService->getAllNotifications());
    }
}