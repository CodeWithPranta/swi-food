<?php

namespace App\Filament\Vendor\Resources\DeliveryChargeResource\Pages;

use App\Filament\Vendor\Resources\DeliveryChargeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryCharges extends ListRecords
{
    protected static string $resource = DeliveryChargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
