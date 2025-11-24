<?php

namespace App\Filament\Resources\ProductionReceiptResource\Pages;

use App\Filament\Resources\ProductionReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductionReceipt extends EditRecord
{
    protected static string $resource = ProductionReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
