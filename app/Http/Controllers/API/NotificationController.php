<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function getNotifications(User $user, float $amount) {
        try {
//            if ($user->)
            $user->notify(new \App\Notifications\PaymentSuccessful($amount));
            return response()->json([
                'success' => true,
                'message' => 'Notifications sent successfully',
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
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

    public function markAsRead(int $id) {
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

    public  function delete(int $id) {
        try {
            Auth::user()->notifications()->find($id)->delete();
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

}
