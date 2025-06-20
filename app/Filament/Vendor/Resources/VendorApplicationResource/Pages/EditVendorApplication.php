<?php

namespace App\Filament\Vendor\Resources\VendorApplicationResource\Pages;

use App\Filament\Vendor\Resources\VendorApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendorApplication extends EditRecord
{
    protected static string $resource = VendorApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
