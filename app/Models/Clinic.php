<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'clinic_number',
        'user',
        'status',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function clinic_tower(): HasMany{
        return $this->hasMany(ClinicTower::class);
    }
}
