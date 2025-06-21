<?php

namespace App\Filament\Vendor\Resources\VendorApplicationResource\Pages;

use App\Filament\Vendor\Resources\VendorApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendorApplications extends ListRecords
{
    protected static string $resource = VendorApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        // Return empty array to remove breadcrumbs
        return [];
    }
}
