<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Location extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'type',
        'capacity',
        'qr_code_path',
        'aisle',
        'rack',
        'level'
    ];

    public function generateAndSaveQrCode(): void
    {
        if (!Storage::disk('public')->exists('qrcodes/locations')) {
            Storage::disk('public')->makeDirectory('qrcodes/locations');
        }

        $qrCodeData = (string) $this->id;
        $fileName = 'location_' . $this->id . '.svg';
        $path = 'qrcodes/locations/' . $fileName;

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
