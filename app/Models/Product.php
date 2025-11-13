<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'unit',
        'min_stock',
        'retail_price',
        'weight',
        'qr_code_path'
    ];

    protected $casts = [
        'retail_price' => 'decimal:2',
        'weight' => 'decimal:3',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
