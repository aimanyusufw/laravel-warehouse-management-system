<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCarrier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'api_key',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }
}
