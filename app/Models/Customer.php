<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['customer_id', 'street', 'district', 'city'];


    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id'); // Assuming 'customer_id' is the foreign key in the customers table
    }
}
