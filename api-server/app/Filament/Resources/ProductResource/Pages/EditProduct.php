<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Filament\Resources\Concerns\FixesImageUploads;
use Illuminate\Support\Facades\Storage;

class EditProduct extends EditRecord
{
    use FixesImageUploads;
    
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Product deleted')
                        ->body('The product has been deleted successfully.')
                ),
            Actions\ViewAction::make(),
            
            // Add image replacement actions
            static::createReplaceImageAction('image_url', 'products', 'Replace Main Image'),
            static::createClearImageAction('image_url', 'Clear Main Image'),
            
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Product updated')
            ->body('The product has been updated successfully.');
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }
        
        return $data;
    }
}
