<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Concerns\FixesImageUploads;
use Illuminate\Support\Facades\Storage;

class EditBrand extends EditRecord
{
    use FixesImageUploads;
    
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            
            // Add image replacement actions
            static::createReplaceImageAction('logo_url', 'brands', 'Replace Logo'),
            static::createClearImageAction('logo_url', 'Clear Logo'),
        ];
    }
} 