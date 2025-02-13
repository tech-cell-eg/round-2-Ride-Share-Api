<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\ResetPasswordMail;
use App\Models\Driver;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use Password;
use Validator;

class AuthController extends Controller
{

    use ApiResponse;

    public function login(LoginRequest $request)
    {



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

    public function register(RegisterRequest $request)
    {


        $otp = rand(100000, 999999);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile_number' => $request->mobile_number,
                'gender' => $request->gender,
                'code' => $otp,
                'expire_at' => Carbon::now()->addMinutes(15),
                'phone_otp' => '12345',
            ]);

            switch ($request->roles) {
                case 'admin':

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
                    $user['roles'] = 'driver';
                    break;
                default:
                    $user->customers()->create([
                        'street' => $request->street,
                        'district' => $request->district,
                        'city' => $request->city
                    ]);
                    $user['roles'] = 'customer';
            }


            try {
                \Log::info('Trying to send OTP email to: ' . $user->email);
                \Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));
                // \Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
                //     $message->to($user->email)
                //         ->subject('Your OTP Code');
                // });
                \Log::info('Trying to send OTP email to: ' . $user->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send OTP email: ' . $e->getMessage());
            }

            return response()->json([
                'user' => $user
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 403);
        }



    }


    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        
        $token = app('auth.password.broker')->createToken($user);
    
        Mail::to($request->email)->send(new ResetPasswordMail($token));
    
        return response()->json(['message' => 'Password reset token sent to your email!']);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password successfully changed']);
        }

        return response()->json(['error' => 'The vrification code is incorrect or expired'], 400);
    }

    public function verifyPhoneOtp(Request $request)
    {
    $request->validate([
        'mobile_number' => 'required',
        'phone_otp' => 'required',
    ]);

    $user = User::where('mobile_number', $request->mobile_number)->first();

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }


    if ($user->phone_otp === $request->phone_otp) {
        $user->update(['phone_otp' => null]); 

        return response()->json(['message' => 'Phone verified successfully']);
    } else {
        return response()->json(['message' => 'Invalid OTP'], 400);
    }
}



    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        if ($user->code !== $request->code || Carbon::now()->gt($user->expire_at)) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }


        $user->code = null;
        $user->expire_at = null;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'OTP verified successfully',
            'token' => $token,
            'data' => $user
        ]);
    }



}
