<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotifController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return ApiResponse::success($notifications, 'Notifications retrieved successfully');
    }

    /**
     * Get count of unread notifications for the authenticated user.
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return ApiResponse::success(['count' => $count], 'Unread notifications count retrieved successfully');
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return ApiResponse::notFound('Notification not found');
        }

        if ($notification->user_id !== Auth::id()) {
            return ApiResponse::forbidden('You are not authorized to update this notification');
        }

        $notification->update(['is_read' => true]);

        return ApiResponse::success($notification, 'Notification marked as read');
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return ApiResponse::success(null, 'All notifications marked as read');
    }

    /**
     * Remove the specified notification.
     */
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return ApiResponse::notFound('Notification not found');
        }

        if ($notification->user_id !== Auth::id()) {
            return ApiResponse::forbidden('You are not authorized to delete this notification');
        }

        $notification->delete();

        return ApiResponse::success(null, 'Notification deleted successfully');
    }

    /**
     * Delete all read notifications for the authenticated user.
     */
    public function deleteAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', true)
            ->delete();

        return ApiResponse::success(null, 'All read notifications deleted successfully');
    }
}