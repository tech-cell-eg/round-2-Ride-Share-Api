<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Http\Requests\ChangeLanguageRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\ContactFormMail;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\Driver;
use App\Models\User;
use Auth;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Log;
use Mail;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

   
    use ApiResponse;
    public function update(UpdateUserRequest $request){

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
      public function changeLanguage(ChangeLanguageRequest $request){
        try {
            $data = $request->validated();
            $user = auth()->user();
            $user->language = $data['language'];
            $user->save();
            return $this->successResponse([
                'language' => $data['language']
            ], 'Language changed successfully!');
        } catch (\Exception $exception) {
            Log::error("Error in Change language" . $exception->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function contactUs(ContactUsRequest $request){

        $email = new ContactFormMail(
            $request->name, 
            $request->email, 
            $request->phone, 
            $request->message
        );
    
        // dd($email);
    
        Mail::to('sara666.s47@gmail.com')->send($email);

        return response()->json(['message' => 'Your message has been sent successfully'], 200);
    }

}

