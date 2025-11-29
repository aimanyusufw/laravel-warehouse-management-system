<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'so_number',
        'customer_id',
        'status',
        'order_date',
        'shipping_carrier_id',
        'shipping_cost',
        'tracking_number'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->so_number)) {
                $model->so_number = 'SO-' . date('Ymd') . '-' . str_pad(SalesOrder::max('id') + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $casts = [
        'order_date' => 'date',
        'shipping_cost' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function carrier()
    {
        return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
