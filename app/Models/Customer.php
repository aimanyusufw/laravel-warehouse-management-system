<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use  SoftDeletes;

    protected $fillable = [
        'name',
        'contact_person',
        'country',
        'note',
        'address',
        'phone',
        'email'
    ];

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }
}
