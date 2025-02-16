<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            Log::error('Error update fcm_token: ' . $e->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function store(User $user = null, $notificationInstance = null) : bool
    {
        try {
            $user->notify($notificationInstance);
            return false;
        } catch (\Exception $e) {
            Log::error('Error Store Notification: ' . $e->getMessage());
            return true;
        }
    }

    public function markAllAsRead() {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return $this->successResponse([], 'Notifications marked as read successfully');
        } catch (\Exception $exception) {
            Log::error('Error Mark All Notifications Read: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function markAsRead(string $id) {
        try {
            $user = auth()->user();
            $notification = $user->unreadNotifications()->where('id', $id)->first();
            if (!$notification) {
                return $this->errorResponse('Notification not found', 404);
            }
            $notification->markAsRead();
            return $this->successResponse([], 'Notification marked as read successfully');
        } catch (\Exception $exception) {
            Log::error('Error Mark A Notification Read: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function deleteAll() {
        try {
            Auth::user()->readNotifications()->delete();
            return $this->successResponse([], 'Notifications deleted successfully');
        } catch (\Exception $exception) {
            Log::error('Error Delete All Notification: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public  function delete(string $id) {
        try {
            $notification = Auth::user()->notifications()->where('id', $id)->first();
            if (!$notification) {
                return $this->errorResponse("Notification not found", 404);
            }
            $notification->delete();
            return $this->successResponse([], 'Notification deleted successfully');
        } catch (\Exception $exception) {
            Log::error('Error Mark A Notification Read: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function index() {
        try {
            $notifications = Auth::user()->notifications()
                ->orderBy('created_at', 'desc')
                ->get();
            return $this->successResponse(NotificationResource::collection($notifications)->toArray(request()));
        } catch (\Exception $exception) {
            Log::error('Error fetching notifications: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function unreadNotifications() {
        try {
            $notifications = Auth::user()->unreadNotifications()
                ->orderBy('created_at', 'desc')
                ->get();
            return $this->successResponse(NotificationResource::collection($notifications)->toArray(request()));
        } catch (\Exception $exception) {
            Log::error('Error Return Unread Notifications: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function readNotifications() {
        try {
            $notifications = Auth::user()->readNotifications()
                ->orderBy('created_at', 'desc')
                ->get();
            return $this->successResponse(NotificationResource::collection($notifications)->toArray(request()));
        } catch (\Exception $exception) {
            Log::error('Error Retrun Read Notifications: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

}
