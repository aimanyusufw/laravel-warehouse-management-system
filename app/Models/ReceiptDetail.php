<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'receipt_id',
        'product_id',
        'batch_number',
        'quantity_received',
        'qc_status'
    ];

    protected $casts = [
        'quantity_received' => 'integer',
    ];

    public function receipt()
    {
        return $this->belongsTo(ProductionReceipt::class, 'receipt_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
