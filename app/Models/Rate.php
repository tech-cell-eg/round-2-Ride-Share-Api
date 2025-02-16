<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'ride_id',
        'rating',
        'review'
    ];

    public function ride(): BelongsTo {
        return $this->belongsTo(Ride::class);
    }

}
