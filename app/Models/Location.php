<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'capacity',
        'qr_code_path',
        'aisle',
        'rack',
        'level'
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
