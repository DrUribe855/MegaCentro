<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TowerConsulting extends Model
{
    use HasFactory;
    public $table = 'tower_cunsultings';
    protected $fillabe = [
        'tower_id',
        'consulting_id',   
    ];

    public function consulting_rooms(): BelongsTo{
        return $this->belongsTo(ConsultingRoom::class);
    }

    public function towers(): BelongsTo{
        return $this->belongsTo(Tower::class);
    }

    public function collection_records(): HasMany{
        return $this->hasMany(CollectionRecord::class);
    }
}
