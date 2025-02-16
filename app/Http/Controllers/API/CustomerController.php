<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function creatCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
                //'customer_id' => ['required','exists:users,id'],
                'street' => ['required','string'],
                'district' => ['required','string'],
                'city' => ['required','string'],
            ]);
        if ($validator->fails()) {
            return ApiResponse::sendResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'Registeration validation error', $validator->errors());
        }

        if (Auth::check()) {
            $user = User::where('id', Auth::id())->first();
            $customer = Customer::create([
                'customer_id' => $user->id,
                'street' => $request->street,
                'district' => $request->district,
                'city' => $request->city,
            ]);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        return ApiResponse::sendResponse(Response::HTTP_CREATED, 'Your data has been updated', $customer);

    }
}
