<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favourit extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

}
