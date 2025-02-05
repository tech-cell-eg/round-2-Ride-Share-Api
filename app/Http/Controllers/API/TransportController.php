<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransportResource;
use App\Models\Transport;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    use ApiResponse;

    public function index() {
        try {
            $transports = TransportResource::collection(Transport::all())->toArray(request());
            return $this->successResponse($transports, 'Transports retrieved successfully.');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}
