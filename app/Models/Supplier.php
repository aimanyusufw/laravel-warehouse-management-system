<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone_number',
        'address',
        'country',
        'tax_id',
        'notes',
    ];

    // Relations
    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class);
    }
}
