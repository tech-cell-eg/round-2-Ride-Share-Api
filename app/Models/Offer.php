<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{

    protected $fillable = [
        'admin_id',
        'title',
        'description',
        'is_available',
    ];

    public function admin(): BelongsTo {
        return $this->belongsTo(Admin::class);
    }

    public function customers(): BelongsToMany {
        return $this->belongsToMany(Customer::class, 'customer_offer', 'offer_id', 'customer_id');
    }

}
