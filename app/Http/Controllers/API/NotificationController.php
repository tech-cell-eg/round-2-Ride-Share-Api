<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Notifications\PaymentSuccessful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function updateNotificationToken(Request $request) {
        try {
            $user = \Illuminate\Support\Facades\Auth::user();
            $user->fcm_token = $request->token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Token saved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function makeNotifications(User $user = null, Notification $notificationInstance = null)
    {
        try {
            if ($user->fcm_token == null) {
                return response()->json([
                    'success' => false,
                    'message' => "User doesn't have FCM Token",
                ]);
            }
            $user->notify($notificationInstance);
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function markAllAsRead() {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return response()->json([
                'success' => true,
                'message' => 'Notifications marked as read successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ],403);
        }
    }

    public function markAsRead(string $id) {
        try {
            $notification = auth()->user()->unreadNotifications->find($id);
            if ($notification) {
                $notification->markAsRead();
            }
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ],403);
        }
    }

    public function deleteAll() {
        try {
            Auth::user()->readNotifications()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Notifications deleted successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ],403);
        }
    }

    public  function deleteNotificaton(string $id) {
        try {
            $notification = Auth::user()->notifications()->find($id);

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ],403);
        }
    }

    public function allNotifications() {
        try {
                $notifications = Auth::user()->notifications()
                    ->orderBy('created_at', 'desc')
                    ->get();
            return response()->json([
                'success' => true,
                'data'    => NotificationResource::collection($notifications)
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function unreadNotifications() {
        try {
            $notifications = Auth::user()->unreadNotifications()
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json([
                'success' => true,
                'data'    => NotificationResource::collection($notifications)
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function readNotifications() {
        try {
            $notifications = Auth::user()->readNotifications()
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json([
                'success' => true,
                'data'    => NotificationResource::collection($notifications)
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ]);
        }
    }

}
