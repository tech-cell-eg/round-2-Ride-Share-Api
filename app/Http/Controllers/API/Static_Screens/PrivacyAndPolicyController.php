<?php

namespace App\Http\Controllers\API\Static_Screens;

use App\Http\Controllers\Controller;
use App\Models\PrivacyAndPolicy;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrivacyAndPolicyController extends Controller
{

    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $content = PrivacyAndPolicy::first();

            if (!$content) {
                return $this->errorResponse('This page content not found.', 404);
            }
            return $this->successResponse($content->toArray());

        } catch (\Exception $exception) {
            Log::error('Error privacy and policy page: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }
}
