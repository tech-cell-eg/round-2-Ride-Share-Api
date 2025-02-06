<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => 'required|exists:vehicles,id',
            'pickup_location' => 'required|string',
            'drop_location' => 'required|string',
            'fare_price' => 'required|numeric',
            'distance' => 'required|numeric',
        ];
    }
}
