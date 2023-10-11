<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
>>>>>>> origin/dev2

class Clinic extends Model
{
    use HasFactory;
    protected $fillable = [
<<<<<<< HEAD
        'id',
        'clinic_number'
        'user',
        'status',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function clinic_tower(): HasMany{
        return $this->hasMany(ClinicTower::class);
    }
=======
        'clinic_number',
        'user_id',
        'status',
    ];

    public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }
>>>>>>> origin/dev2
}
