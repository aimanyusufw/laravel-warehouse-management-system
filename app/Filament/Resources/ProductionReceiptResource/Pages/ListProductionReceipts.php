<?php

namespace App\Filament\Resources\ProductionReceiptResource\Pages;

use App\Filament\Resources\ProductionReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductionReceipts extends ListRecords
{
    protected static string $resource = ProductionReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
