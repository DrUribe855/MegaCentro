<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Residue extends Model
{
    use HasFactory;
    protected $fillable = [
        'residue_name',
        'residue_type_id',
        'price',
    ];

    public function residue_types(): BelongsTo{
        return $this->belongsTo(ResidueType::class);
    }

    public function waste_collection(): HasMany{
        return $this->hasMany(Waste_collection::class);
    }
}
