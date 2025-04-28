<?php

namespace App\Filament\Resources\ShippingPolicyResource\Pages;

use App\Filament\Resources\ShippingPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\ShippingPolicy;

class EditShippingPolicy extends EditRecord
{
    protected static string $resource = ShippingPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // If the edited shipping policy is active, deactivate all other shipping policies
        if ($this->record->is_active) {
            ShippingPolicy::where('id', '!=', $this->record->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 