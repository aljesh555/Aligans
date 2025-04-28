<?php

namespace App\Filament\Resources\ShippingFormResource\Pages;

use App\Filament\Resources\ShippingFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingForm extends EditRecord
{
    protected static string $resource = ShippingFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
