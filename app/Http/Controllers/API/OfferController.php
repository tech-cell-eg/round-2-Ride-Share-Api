<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CollectOfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Customer;
use App\Models\Offer;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

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

    public function collectOffer(CollectOfferRequest $request) {
        try {
            $data = $request->validated();
            $customer = Customer::where('customer_id', Auth::id())->first();
            $customer->offers()->SyncWithoutDetaching($data['offer_id']);
            $offer = Offer::find($data['offer_id']);
            $offer->update(['is_available' => false]);
            return $this->successResponse([], 'Collected successfully.');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

}
