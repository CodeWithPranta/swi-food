<?php

namespace App\Filament\Vendor\Resources\PaymentMethodResource\Pages;

use App\Filament\Vendor\Resources\PaymentMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;
}
