<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{

    protected $fillable = [
        'admin_id',
        'type'
    ];

    public function users() :BelongsTo {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function offers() :HasMany {
        return $this->hasMany(Offer::class, 'admin_id');
    }

}
