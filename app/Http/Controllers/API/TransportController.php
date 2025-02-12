<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransportResource;
use App\Models\Transport;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransportController extends Controller
{
    use ApiResponse;

    public function index() {
        try {
            $transports = TransportResource::collection(Transport::all())->toArray(request());
            return $this->successResponse($transports, 'Transports retrieved successfully.');
        } catch (\Exception $exception) {
            Log::error('Error List Of Transports: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

}
