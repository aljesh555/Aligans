<?php

namespace App\Filament\Resources\LogoResource\Pages;

use App\Filament\Resources\LogoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Logo;

class CreateLogo extends CreateRecord
{
    protected static string $resource = LogoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function handleRecordCreation(array $data): Model
    {
        // Extract the uploaded file path from the form data
        $filePath = $data['logo'];
        
        // Create the logo record 
        $logo = Logo::create([
            'image_path' => 'storage/' . $filePath,
            'is_active' => $data['is_active'] ?? false
        ]);
        
        // If this logo is set as active, deactivate all others
        if ($data['is_active'] ?? false) {
            Logo::where('id', '!=', $logo->id)->update(['is_active' => false]);
        }
        
        return $logo;
    }
} 