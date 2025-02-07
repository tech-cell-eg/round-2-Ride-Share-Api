<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
class UserController extends Controller
{

    use ApiResponseTrait;
    public function update(Request $request){


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => 'required|string',
            'street' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
        ]);


        $user = User::findOrFail($request->id); 
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
}
