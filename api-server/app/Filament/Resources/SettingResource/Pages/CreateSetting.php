<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if($data['type'] === 'repeater') {
            $data['value'] = json_encode($data['repeaterValue']);
            unset($data['repeaterValue']);
        }
        elseif($data['type'] === 'image') {
            $data['value'] = json_encode($data['fileValue']);
            unset($data['fileValue']);
        }
        elseif($data['type'] === 'file') {
            $data['value'] = json_encode($data['fileValue']);
            unset($data['fileValue']);
        }
        elseif($data['type'] === 'string') {
            $data['value'] = json_encode($data['stringValue']);
            unset($data['stringValue']);
        }

        // Remove tabs or whitespace from key and description
        $data['key'] = trim($data['key']);
        if (isset($data['description'])) {
            $data['description'] = trim($data['description']);
        }
        
        return $data;
    }
}
