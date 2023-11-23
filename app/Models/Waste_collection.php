<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waste_collection extends Model
{
    use HasFactory;
    protected $fillable = [
        'weight',
        'garbage_bags', 
    ];

    public function residues(): BelongsTo{
        return $this->belongsTo(Residue::class);
    }

    public function collection_logs(): BelongsTo{
        return $this->belongsTo(CollectionLog::class);
    }
}
