<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'clinic_id',
        'residue_id',
        'weight',
    ];

    public function waste_collection(): HasMany{
        return $this->hasMany(Waste_collection::class, 'collection_logs_id');
    }

    public function residues(): BelongsTo{
        return $this->belongsTo(Residue::class);
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function clinic(): BelongsTo{
        return $this->belongsTo(Clinic::class);
    }
}