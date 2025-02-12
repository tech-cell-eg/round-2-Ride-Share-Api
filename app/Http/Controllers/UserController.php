<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\Driver;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
class UserController extends Controller
{

    use ApiResponseTrait;
    public function update(UpdateUserRequest $request)
    {

        $user = auth()->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
        ]);

        if ($user->roles == 'customer') {

            $customer = Customer::where('customer_id', $user->id)->first();
            if ($customer) {
                $customer->update([
                    'street' => $request->street,
                    'district' => $request->district,
                    'city' => $request->city,
                ]);
            } else {
                $customer = Customer::create([
                    'customer_id' => $user->id,
                    'street' => $request->street,
                    'district' => $request->district,
                    'city' => $request->city,
                ]);
            }
        }

        return $this->successResponse([
            'name' => $user->name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'city' => $customer->city,
            'district' => $customer->district,
            'street' => $customer->street
        ], 'Profile updated successfully!');
    }


    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }


    public function deleteAccount(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->roles; 

        

            if ($role == 'admin') {

                $user->offers()->delete(); 

                Admin::where('admin_id', $user->id)->delete();
            } elseif ($role == 'customer') {

                $user->offers()->delete(); 
                Customer::where('customer_id', $user->id)->delete();
            } elseif ($role == 'driver') {

                $user->vehicles()->delete(); 
                $user->rides()->delete(); 
                Driver::where('driver_id', $user->id)->delete();
            }


            $user->delete();


            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'message' => 'Account, offers, and related data deleted successfully'
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

}
