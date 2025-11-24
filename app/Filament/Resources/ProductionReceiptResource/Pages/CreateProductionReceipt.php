<?php

namespace App\Filament\Resources\ProductionReceiptResource\Pages;

use App\Filament\Resources\ProductionReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductionReceipt extends CreateRecord
{
    protected static string $resource = ProductionReceiptResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
