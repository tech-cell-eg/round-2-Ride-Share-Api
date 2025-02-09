<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{

    use ApiResponseTrait;
    
    public function login(LoginRequest $request){


 
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
  
    public function register (Request $request) {

//        dd($request->all());

        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'mobile_number' => 'nullable|string|max:255',
            'role' => 'required|in:admin,customer,driver|max:255',
            'gender' => 'nullable|in:male,female,other|max:255',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile_number' => $request->mobile_number,
                'gender' => $request->gender,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            switch ($request->role) {
                case 'admin':
                    // create in table admin
                break;
                case 'driver':
                    if (empty($request->license_number))
                        return response()->json([
                            'success' => false,
                            'message' => 'License number is required'
                        ]);
                    Driver::create([
                        'driver_id' => $user->id,
                        'license_number' => $request->license_number
                    ]);
                    $user['role'] = 'driver';
                break;
                default:
                    $user->customers()->create([
                        'street'   => $request->street,
                        'district' => $request->district,
                        'city'     => $request->city
                    ]);
                    $user['role'] = 'customer';
            }
            return response()->json([
                'access_token' => $token,
                'user' => $user
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 403);
        }

    }
    

}
