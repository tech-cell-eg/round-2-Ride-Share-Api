<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{

    protected $fillable = [
        'driver_id',
        'transport_id',
        'fuel',
        'color',
        'model',
        'license_plate',
        'manufacture_year',
        'maker'
    ];

    public function transport(): BelongsTo
    {
        return $this->belongsTo(Transport::class);
    }

    public function driver(): BelongsTo{
        return $this->belongsTo(Driver::class);
    }

    public function rides(): HasMany {
        return $this->hasMany(Ride::class);
    }

}
