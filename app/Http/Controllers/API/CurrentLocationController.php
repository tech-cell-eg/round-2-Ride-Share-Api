<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurrentLocationRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
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
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
