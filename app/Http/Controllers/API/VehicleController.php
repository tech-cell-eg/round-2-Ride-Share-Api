<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Transport;
use App\Models\Vehicle;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            Log::error('Error Get Available Cars: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function ShowCar($id) {
        try {
            $car = Vehicle::where('id', $id)->first();
            if (!$car) {
                return $this->errorResponse('Car not found', 404);
            }
            return $this->successResponse((new CarResource($car))->toArray(request()), 'Car found');
        } catch (\Exception $exception) {
            Log::error('Error get car details: ' . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }


}
