<?php

namespace App\Filament\Resources\LogoResource\Pages;

use App\Filament\Resources\LogoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Logo;
use Illuminate\Support\Facades\Storage;

class EditLogo extends EditRecord
{
    protected static string $resource = LogoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Check if a new logo was uploaded
        if (isset($data['logo'])) {
            // Delete the old image if it exists
            if ($record->image_path) {
                $oldPath = str_replace('storage/', '', $record->image_path);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            // Update with the new image path
            $record->image_path = 'storage/' . $data['logo'];
        }
        
        // Update the active status
        $record->is_active = $data['is_active'] ?? false;
        $record->save();
        
        // If this logo is set as active, deactivate all others
        if ($data['is_active'] ?? false) {
            Logo::where('id', '!=', $record->id)->update(['is_active' => false]);
        }
        
        return $record;
    }
} 