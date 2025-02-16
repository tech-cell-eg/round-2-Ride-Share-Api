<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends \App\Http\Controllers\Controller
{

    use ApiResponse;
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        $data['token'] = $user->createToken('Ride Share')->plainTextToken;
        $data['name'] = $user->name;
        $data['email'] = $user->email;

        return $this->successResponse($data, 'User created successfully', Response::HTTP_CREATED);

    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (Auth::attempt($data)) {
            $user = Auth::user();
            $data['token'] = $user->createToken('API TOKEN')->plainTextToken;

            return $this->successResponse($data, 'User Loged in successfully', Response::HTTP_CREATED);
        }
        return $this->errorResponse([], 'these credentials do not match our records.', Response::HTTP_UNAUTHORIZED);
    }
}
