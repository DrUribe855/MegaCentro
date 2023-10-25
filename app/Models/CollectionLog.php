<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'clinic_id',
        'residue_id',
        'weight',
    ];

    public function residues(): BelongsTo{
        return $this->belongsTo(Residue::class);
    }

    public function clinic(): BelongsTo{
        return $this->belongsTo(Clinic::class);
    }
}