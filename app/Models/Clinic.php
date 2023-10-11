<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Clinic extends Model
{
    use HasFactory;
    protected $fillable = [
<<<<<<< HEAD
        'id',
        'clinic_number',
        'user',
=======
 
        'clinic_number',
        'user_id',
>>>>>>> origin/master
        'status',
    ];

   public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function clinic_tower(): HasMany{
        return $this->hasMany(ClinicTower::class);
    }
 
        

    
 
}
