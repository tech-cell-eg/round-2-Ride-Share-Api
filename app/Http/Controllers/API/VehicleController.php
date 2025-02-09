<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Transport;
use App\Models\Vehicle;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{

    use ApiResponse;

    public function availableCars() {
        try {
            $carTransport = Transport::where('name', 'car')->first();
            if (!$carTransport) {
                return $this->errorResponse('Car transport type not found', 404);
            }
            $cars = Vehicle::where('transport_id', $carTransport->id)
                ->where('is_available', true)->get();
            return $this->successResponse(CarResource::collection($cars)->toArray(request()), 'cars available');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }

    public function ShowCar(Vehicle $vehicle) {
        try {
            Transport::findOrFail($vehicle->transport_id);
            return $this->successResponse((new CarResource($vehicle))->toArray(request()), 'Car found');
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 500);
        }
    }


}
