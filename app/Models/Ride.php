<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ride extends Model
{

    protected $fillable = [
        'customer_id',
        'driver_id',
        'vehicle_id',
        'pickup_location',
        'drop_location',
        'fare_price',
        'distance',
        'ride_status',
    ];

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function driver(): BelongsTo {
        return $this->belongsTo(Driver::class);
    }

    public function vehicle(): BelongsTo {
        return $this->belongsTo(Vehicle::class);
    }

}
