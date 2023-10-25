<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicTower extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillabe = [
        'tower_id',
        'consulting_id',   
    ];

    public function clinic(): BelongsTo{
        return $this->belongsTo(Clinic::class);
    }

    public function towers(): BelongsTo{
        return $this->belongsTo(Tower::class);
    }

    public function collection_log(): HasMany{
        return $this->hasMany(CollectionLog::class);
    }
}