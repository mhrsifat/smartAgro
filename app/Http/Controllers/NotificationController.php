<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user (both read and unread)
     */
    public function all(): JsonResponse
    {
        try {
            $notifications = auth()->user()
                ->notifications()
                ->latest()
                ->take(20) // Limit to 20 most recent
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'message' => $notification->data['message'] ?? 'New notification',
                        'url' => $notification->data['url'] ?? null,
                        'type' => $notification->type,
                        'created_at' => $notification->created_at,
                        'read_at' => $notification->read_at,
                        'is_unread' => is_null($notification->read_at),
                    ];
                });

            return response()->json($notifications);
        } catch (\Exception $e) {
            \Log::error('Error fetching all notifications: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch notifications'], 500);
        }
    }

    /**
     * Get unread notifications for the authenticated user
     */
    public function unread(): JsonResponse
    {
        try {
            $notifications = auth()->user()
                ->unreadNotifications()
                ->latest()
                ->take(20) // Limit to 20 most recent
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'message' => $notification->data['message'] ?? 'New notification',
                        'url' => $notification->data['url'] ?? null,
                        'type' => $notification->type,
                        'created_at' => $notification->created_at,
                    ];
                });

            return response()->json($notifications);
        } catch (\Exception $e) {
            \Log::error('Error fetching unread notifications: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch notifications'], 500);
        }
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(string $id): JsonResponse
    {
        try {
            $notification = auth()->user()
                ->unreadNotifications()
                ->where('id', $id)
                ->first();

            if (!$notification) {
                return response()->json(['error' => 'Notification not found'], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error marking notification as read: ' . $e->getMessage());
            
            return response()->json(['error' => 'Failed to mark notification as read'], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        try {
            $user = auth()->user();
            $count = $user->unreadNotifications()->count();
            
            if ($count === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'No unread notifications to mark',
                    'count' => 0
                ]);
            }
            
            $user->unreadNotifications()->update(['read_at' => now()]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Marked {$count} notifications as read",
                    'count' => $count
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Marked {$count} notifications as read",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error marking all notifications as read: ' . $e->getMessage());
            
            return response()->json(['error' => 'Failed to mark all notifications as read'], 500);
        }
    }

}