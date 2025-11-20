<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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

    public function generateAndSaveQrCode(): void
    {
        if (!Storage::disk('public')->exists('qrcodes/products')) {
            Storage::disk('public')->makeDirectory('qrcodes/products');
        }

        $qrCodeData = (string) $this->sku;
        $fileName = 'location_' . $this->sku . '.svg';
        $path = 'qrcodes/products/' . $fileName;

        \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
            ->format('svg')
            ->generate($qrCodeData, storage_path('app/public/' . $path));

        $this->qr_code_path = $path;
        $this->saveQuietly();
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
