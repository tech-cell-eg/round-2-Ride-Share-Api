<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavouritRequest;
use App\Models\Favourit;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavouritController extends Controller
{

    use ApiResponse;

    public function index() {
        try {
            $favourites = Auth::user()->favourites()->get()->toArray();
            return $this->successResponse($favourites);
        } catch (\Exception $exception){
            Log::error('Error get list of favourites: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function store(FavouritRequest $request) {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            Favourit::create($data);
            return $this->successResponse([], 'Added to favourit');
        } catch (\Exception $exception) {
            Log::error('Error to add favourite: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function destroy($vehicle_id) {
        try {
            $favourit = Favourit::where('user_id', Auth::id())->where('vehicle_id', $vehicle_id)->first();
            $favourit->delete();
            return $this->successResponse([], 'Removed from favourit');
        } catch (\Exception $exception) {
            Log::error('Error delete vehicle: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

}
