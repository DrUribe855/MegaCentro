<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'tower_consulting_id',
        'residue_id',
        'weight',
    ];

    public function residues(): BelongsTo{
        return $this->belongsTo(Residue::class);
    }

    public function tower_consultings(): BelongsTo{
        return $this->belongsTo(TowerConsulting::class);
    }
}
