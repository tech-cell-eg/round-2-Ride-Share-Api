<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    use HasFactory;

    protected $primaryKey = 'customer_id';
    protected $fillable = ['customer_id' , 'street' , 'city' ,'disrtict'];



    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id'); // Assuming 'customer_id' is the foreign key in the customers table
    }

    public function offers() :BelongsToMany {
        return $this->belongsToMany(Offer::class, 'customer_offer', 'customer_id', 'offer_id');
    }


}
