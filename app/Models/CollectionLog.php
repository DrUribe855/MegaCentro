<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'tower_clinic_id',
        'residue_id',
        'weight',
    ];

    public function residues(): BelongsTo{
        return $this->belongsTo(Residue::class);
    }

    public function clinic_tower(): BelongsTo{
        return $this->belongsTo(ClinicTower::class);
    }
}