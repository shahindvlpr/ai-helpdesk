// app/Http/Controllers/Api/NotificationController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        $query = Notification::byUser(Auth::id());

        if ($request->has('unread_only')) {
            $query->unread();
        }

        $notifications = $query->latest()->paginate(20);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::byUser(Auth::id())->unread()->count()
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::byUser(Auth::id())->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted'
        ]);
    }
}