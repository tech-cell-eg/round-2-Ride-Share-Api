<?php

namespace App\Http\Controllers\API;


use Approllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use ApiResponse;
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users'],
            'gender' => ['required','string'],
            'mobile_number' => ['required','unique:users'],
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return ApiResponse::sendResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'Registeration validation error', $validator->errors());
        }

        $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'mobile_number' => $request->mobile_number,
                'password' => Hash::make($request->password),
            ]);

        $data['token'] = $user->createToken('Ride Share')->plainTextToken;
        $data['name'] = $user->name;
        $data['email'] = $user->email;

        return $this->successResponse($data, 'User created successfully', Response::HTTP_CREATED);

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Login validation error', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $data['token'] = $user->createToken('API TOKEN')->plainTextToken;
            $data['name'] = $user->name;
            $data['email'] = $user->email;

            return $this->successResponse($data, 'User Loged in successfully', Response::HTTP_CREATED);
        }
        return $this->errorResponse([], 'these credentials do not match our records.', Response::HTTP_UNAUTHORIZED);
    }
}
