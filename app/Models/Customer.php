<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'customer_id'
    ];

    public function users() :BelongsTo{
        return $this->belongsTo(User::class, 'customer_id', 'id') ;
    }

    public function rides(): HasMany {
        return $this->hasMany(Ride::class);
    }


}
