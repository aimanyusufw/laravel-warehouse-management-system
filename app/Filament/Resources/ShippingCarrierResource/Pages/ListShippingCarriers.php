<?php

namespace App\Filament\Resources\ShippingCarrierResource\Pages;

use App\Filament\Resources\ShippingCarrierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingCarriers extends ListRecords
{
    protected static string $resource = ShippingCarrierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
