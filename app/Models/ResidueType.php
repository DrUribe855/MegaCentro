<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidueType extends Model
{
    use HasFactory;

     protected $fillable = [
        'residue_category',
        'residue_type',
    ];

    public function residues(): HasMany{
        return $this->hasMany(Residue::class);
    }
}
