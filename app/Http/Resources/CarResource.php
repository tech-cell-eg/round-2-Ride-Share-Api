<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'driver_id' => $this->driver_id,
            'transport_id' => $this->transport_id,
            'license_plate' => $this->license_plate,
            'fuel' => $this->fuel,
            'color' => $this->color,
            'model' => $this->model,
            'manifactur_year' => $this->manifactur_year,
            'manifactur_company' => $this->manifactur_company,
            'is_available' => $this->is_available,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d h:i'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d h:i'),
        ];
    }
}
