<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
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

    public function login (Request $request) {

        $validated = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'error' => 'Invalid Credentials'
                ],403);
            }
            $user = User::where('email', $credentials['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'user' => $user
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ],403);
        }

    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'User has been logged out'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to log out',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
