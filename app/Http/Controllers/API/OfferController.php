<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Traits\ApiResponse;

class OfferController extends Controller
{
    use ApiResponse;
    public function index() {
        try {
            $offers = Offer::where('is_available', true)->get();
            return $this->successResponse(OfferResource::collection($offers)->resolve(), 'Offers retrieved successfully.');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

}
