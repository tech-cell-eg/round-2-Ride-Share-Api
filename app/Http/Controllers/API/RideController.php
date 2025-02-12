<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RideRequest;
use App\Http\Resources\RideResource;
use App\Models\Driver;
use App\Models\Ride;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\PaymentSuccessful;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{

    use ApiResponse;

    public function store(RideRequest $request) {
        try {
            $dataValidated = $request->validated();
            $vehicle = Vehicle::findOrFail($dataValidated['vehicle_id']);
            $dataValidated['customer_id'] = Auth::id();
            $dataValidated['driver_id'] = $vehicle->driver_id;
            $ride = Ride::where('vehicle_id', $vehicle->id)
                ->where('customer_id', Auth::id())->first();
            if ($ride) {
                return $this->errorResponse('Ride already exists');
            }
            $dataValidated['status'] = 'requested';
            $ride = Ride::create($dataValidated);
            $rideData = [];
            $rideData['customer_name'] = Auth::user()->name;
            $rideData['pickup_location'] = $dataValidated['pickup_location'];
            $rideData['drop_location'] = $dataValidated['drop_location'];
            $rideData['fare_price'] = $dataValidated['fare_price'];
            $rideData['distance'] = $dataValidated['distance'];
            $note = new NotificationController();
            $note->store(User::findOrFail($vehicle->driver_id), new \App\Notifications\RideRequest($rideData));
            return $this->successResponse((new RideResource($ride))->toArray(request()), 'Ride created successfully.');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }

}
