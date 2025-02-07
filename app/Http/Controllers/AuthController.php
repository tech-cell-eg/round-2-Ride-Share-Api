<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{

    use ApiResponseTrait;
    
    public function login(Request $request){


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422);
        }

        $user = User::where('email', $request->email)->first();
        $customer = $user->customer;

        if (!$user || !Auth::attempt($request->only('email', 'password'))) {
            return $this->errorResponse('Unauthorized', 401);
        }

        // Generate Sanctum token
        $token = $user->createToken('API Token')->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'city' => $customer->city,
            'district' => $customer->district,
            'street' => $customer->street,
        ], 'Login successful');
    }
    

}
