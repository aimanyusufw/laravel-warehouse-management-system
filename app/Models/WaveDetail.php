<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaveDetail extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'picking_wave_id',
        'order_detail_id',
        'inventory_id',
        'quantity_to_pick'
    ];

    protected $casts = [
        'quantity_to_pick' => 'integer',
    ];

    public function pickingWave()
    {
        return $this->belongsTo(PickingWave::class);
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
