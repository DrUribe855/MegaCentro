<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waste_collection extends Model
{
    use HasFactory;

    protected $table = 'waste_collections';

    protected $fillable = [
        'collection_logs_id',
        'id_residue',
        'weight',
        'garbage_bags', 
    ];

    public function residues(): BelongsTo{
        return $this->belongsTo(Residue::class, 'id_residue');
    }

    public function collection_logs(): BelongsTo{
        return $this->belongsTo(CollectionLog::class, 'collection_logs_id');
    }
}
