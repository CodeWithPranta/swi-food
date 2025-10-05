<?php

namespace App\Filament\Resources\VendorApplicationResource\Pages;

use App\Filament\Resources\VendorApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Notifications\VendorApplicationStatusNotification;

class EditVendorApplication extends EditRecord
{
    protected static string $resource = VendorApplicationResource::class;

    protected function afterSave(): void
    {
        // Send notification when approval status changes
        if ($this->record->wasChanged('is_approved')) {
            $this->record->user->notify(new VendorApplicationStatusNotification($this->record));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
