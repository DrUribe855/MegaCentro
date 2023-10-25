<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tower extends Model
{
    use HasFactory;
    protected $table = 'towers';

    public function clinic(): HasMany{
        return $this->hasMany(Clinic::class);
    }
}
