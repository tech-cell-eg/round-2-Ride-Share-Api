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
            $data = $request->validated();
            $vehicle = Vehicle::findOrFail($data['vehicle_id']);
            $user = User::findOrFail($vehicle->driver_id);
            $ride = Ride::create([
                'customer_id' => $user->id,
                'vehicle_id' => $data['vehicle_id'],
                'driver_id' => $user->id,
                'status' => 'requested',
                'pickup_location' => $data['pickup_location'],
                'drop_location' => $data['drop_location'],
                'fare_price' => $data['fare_price'],
                'distance' => $data['distance'],
            ]);
            // send notifications for driver
            $rideData = [];
            $rideData['customer_name'] = $user->name;
            $rideData['pickup_location'] = $data['pickup_location'];
            $rideData['drop_location'] = $data['drop_location'];
            $rideData['fare_price'] = $data['fare_price'];
            $rideData['distance'] = $data['distance'];
            $note = new NotificationController();
            $note->store($user, new \App\Notifications\RideRequest($rideData));
            return $this->successResponse((new RideResource($ride))->toArray(request()), 'Ride created successfully.');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }

}
