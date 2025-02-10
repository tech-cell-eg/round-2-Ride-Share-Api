<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

}
