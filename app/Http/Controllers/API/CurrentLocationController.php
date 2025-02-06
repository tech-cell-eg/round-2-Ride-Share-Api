<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class CurrentLocationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validated = $request->validate([
                'ip' => 'required|ip'
            ]);
            $location = Location::get($validated['ip']);
            if (!$location || $location->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location not found for the provided IP'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $location
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ]);
        }
    }
}
