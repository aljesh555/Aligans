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
            $data['value'] = $data['repeaterValue'];
            unset($data['repeaterValue']);
        }
        if($data['type'] === 'image') {
            $data['value'] = $data['fileValue'];
            unset($data['fileValue']);
        }
        if($data['type'] === 'file') {
            $data['value'] = $data['fileValue'];
            unset($data['fileValue']);
        }
        if($data['type'] === 'string') {
            $data['value'] = $data['stringValue'];
            unset($data['stringValue']);
        }

        dd($data);
        return $data;
    }
}
