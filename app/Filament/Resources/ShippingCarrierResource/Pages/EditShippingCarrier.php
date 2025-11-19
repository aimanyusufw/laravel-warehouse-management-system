<?php

namespace App\Filament\Resources\ShippingCarrierResource\Pages;

use App\Filament\Resources\ShippingCarrierResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingCarrier extends EditRecord
{
    protected static string $resource = ShippingCarrierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
