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
    ];

    public function residue_types(): BelongsTo{
        return $this->belongsTo(ResidueType::class);
    }

    public function collection_log(): HasMany{
        return $this->hasMany(CollectionLog::class);
    }
}
