<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavouritRequest;
use App\Models\Favourit;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class FavouritController extends Controller
{

    use ApiResponse;

    public function index() {
        try {
            $favourites = Auth::user()->favourites()->get()->toArray();
            return $this->successResponse($favourites);
        } catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(FavouritRequest $request) {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            Favourit::create($data);
            return $this->successResponse([], 'Added to favourit');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy($vehicle_id) {
        try {
            $favourit = Favourit::where('user_id', Auth::id())->where('vehicle_id', $vehicle_id)->first();
            $favourit->delete();
            return $this->successResponse([], 'Removed from favourit');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

}
