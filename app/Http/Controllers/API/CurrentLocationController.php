<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurrentLocationRequest;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;

class CurrentLocationController extends Controller
{

    use ApiResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(CurrentLocationRequest $request)
    {
        try {
            $location = Location::get($request->validated('ip'));
            if (!$location || $location->isEmpty()) {
                return $this->errorResponse('Location not found for the provided IP', 404);
            }
            return $this->successResponse($location);
        } catch (\Exception $e) {
            Log::error('Error Enable Location: ' . $e->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }
}
