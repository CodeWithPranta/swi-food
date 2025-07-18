<?php

namespace App\Filament\Vendor\Resources\FoodResource\Pages;

use App\Filament\Vendor\Resources\FoodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFood extends ListRecords
{
    protected static string $resource = FoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
