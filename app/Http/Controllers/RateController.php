<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateRequest;
use App\Models\Rate;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RateController extends Controller
{

    use ApiResponse;

    public function store (RateRequest $request) {
        try {
            $data = $request->validated();
            Rate::create($data);
            return $this->successResponse([], 'Rate added successfully');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

}
