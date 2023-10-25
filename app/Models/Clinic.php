<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    use HasFactory;
    protected $fillable = [
        'clinic_number',
        'user_id',
        'tower_id',
        'status', 
    ];

    public function towers(): BelongsTo{
        return $this->belongsTo(Tower::class);
    }

    public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function collection_log() : HasMany{
        return $this->hasMany(CollectionLog::class);
    }
}
