<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'customer_id'
    ];

    public function users() :BelongsTo{
        return $this->belongsTo('App\Models\User', 'customer_id', 'id') ;
    }

}
