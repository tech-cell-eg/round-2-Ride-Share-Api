<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    use ApiResponse;
    public function update(Request $request) {
        try {
            $user = Auth::user();
            $user->fcm_token = $request->token;
            $user->save();
            return $this->successResponse([], 'Token saved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(User $user = null, Notification $notificationInstance = null)
    {
        try {
            if ($user->fcm_token == null) {
                return $this->errorResponse("User doesn't have FCM Token");
            }
            $user->notify($notificationInstance);
            return $this->successResponse([], 'Notification send successfully');
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function markAllAsRead() {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return $this->successResponse([], 'Notifications marked as read successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function markAsRead(string $id) {
        try {
            $notification = auth()->user()->unreadNotifications->find($id);
            if ($notification) {
                $notification->markAsRead();
            }
            return $this->successResponse([], 'Notification marked as read successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function deleteAll() {
        try {
            Auth::user()->readNotifications()->delete();
            return $this->successResponse([], 'Notifications deleted successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public  function delete(string $id) {
        try {
            $notification = Auth::user()->notifications()->find($id);
            if (!$notification) {
                return $this->errorResponse("Notification not found", 404);
            }
            $notification->delete();
            return $this->successResponse([], 'Notification deleted successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function index() {
        try {
            $notifications = Auth::user()->notifications()
                ->orderBy('created_at', 'desc')
                ->get();
            return $this->successResponse(NotificationResource::collection($notifications));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function unreadNotifications() {
        try {
            $notifications = Auth::user()->unreadNotifications()
                ->orderBy('created_at', 'desc')
                ->get();
            return $this->successResponse(NotificationResource::collection($notifications));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function readNotifications() {
        try {
            $notifications = Auth::user()->readNotifications()
                ->orderBy('created_at', 'desc')
                ->get();
            return $this->successResponse(NotificationResource::collection($notifications));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

}
