<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PickingWave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wave_number',
        'status',
        'picker_user_id'
    ];

    public function picker()
    {
        return $this->belongsTo(User::class, 'picker_user_id');
    }

    public function details()
    {
        return $this->hasMany(WaveDetail::class);
    }
}
