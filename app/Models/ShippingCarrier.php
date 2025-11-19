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
        "service_name",
        "integration_endpoint",
        "cut_off_time",
        "base_rate",
        "min_weight",
        "max_weight",
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
