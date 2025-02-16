<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = [
        'driver_id',
        'license_number',
    ];

    public function vehicles():hasMany {
        return $this->hasMany(Vehicle::class);
    }

    public function rides():hasMany {
        return $this->hasMany(Driver::class);
    }

}
