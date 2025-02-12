<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Models\Rate;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class RateController extends Controller
{

    use ApiResponse;

    public function store (RateRequest $request) {
        try {
            $data = $request->validated();
            Rate::create($data);
            return $this->successResponse([], 'Rate added successfully');
        } catch (\Exception $exception) {
            Log::error('Error store rate: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

}
