<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{

    use ApiResponse;

    public function index() {
        try {
            $settings = Setting::all();
            if ($settings->isEmpty()) {
                return $this->errorResponse('settings not found', 404);
            }
            return $this->successResponse($settings->toArray());
        } catch (\Exception $exception) {
            Log::error("Get all settings" . $exception->getMessage());
            return $this->errorResponse('Something went wrong');
        }
    }

}
