<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsultingRoom extends Model
{
    use HasFactory;
    public $table = 'cosulting_rooms';
    protected $fillable = [
        'number_consulting',
        'user_id',
        'status',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function tower_consulting(): HasMany{
        return $this->hasMany(TowerConsulting::class);
    }
}
