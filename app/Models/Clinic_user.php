<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Clinic_user extends Model
{
    use HasFactory;

    protected $table = "clinic_users";

    protected $fillable = [
        'user_id',
        'clinic_id',
        'role',
    ];

    public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function clinic() : BelongsTo{
        return $this->belongsTo(Clinic::class);
    }
}
