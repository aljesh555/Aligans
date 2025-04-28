<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if($data['type'] === 'repeater') {
            $data['repeaterValue'] = json_decode($data['value'], true);
            unset($data['value']);
        }
        if($data['type'] === 'image') {
            $data['fileValue'] = $data['value'];
            unset($data['value']);
        }
        if($data['type'] === 'file') {
            $data['fileValue'] = $data['value'];
            unset($data['value']);
        }
        if($data['type'] === 'string') {
            $data['stringValue'] = $data['value'];
            unset($data['value']);
        }
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
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
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
