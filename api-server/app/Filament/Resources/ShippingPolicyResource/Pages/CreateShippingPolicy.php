<?php

namespace App\Filament\Resources\ShippingPolicyResource\Pages;

use App\Filament\Resources\ShippingPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ShippingPolicy;

class CreateShippingPolicy extends CreateRecord
{
    protected static string $resource = ShippingPolicyResource::class;

    protected function afterCreate(): void
    {
        // If the created shipping policy is active, deactivate all other shipping policies
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