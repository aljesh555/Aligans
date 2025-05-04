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
            if (is_string($data['value'])) {
                $decoded = json_decode($data['value'], true);
                $data['repeaterValue'] = $decoded ?: [];
            } else {
                $data['repeaterValue'] = $data['value'] ?: [];
            }
            unset($data['value']);
        }
        if($data['type'] === 'image') {
            if (is_string($data['value'])) {
                try {
                    $decoded = json_decode($data['value'], true);
                    if (is_string($decoded)) {
                        $data['fileValue'] = $decoded;
                    } 
                    else if (is_array($decoded) && count($decoded) === 1) {
                        $data['fileValue'] = reset($decoded);
                    }
                    else {
                        $data['fileValue'] = $decoded;
                    }
                } catch (\Exception $e) {
                    $data['fileValue'] = $data['value'];
                }
            } else {
            $data['fileValue'] = $data['value'];
            }
            unset($data['value']);
        }
        if($data['type'] === 'file') {
            if (is_string($data['value'])) {
                try {
                    $decoded = json_decode($data['value'], true);
                    $data['fileValue'] = $decoded ?: $data['value'];
                } catch (\Exception $e) {
                    $data['fileValue'] = $data['value'];
                }
            } else {
            $data['fileValue'] = $data['value'];
            }
            unset($data['value']);
        }
        if($data['type'] === 'string') {
            if (is_string($data['value'])) {
                try {
                    $decoded = json_decode($data['value'], true);
                    $data['stringValue'] = $decoded ?: $data['value'];
                } catch (\Exception $e) {
                    $data['stringValue'] = $data['value'];
                }
            } else {
            $data['stringValue'] = $data['value'];
            }
            unset($data['value']);
        }
        return $data;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if($data['type'] === 'repeater') {
            $data['value'] = json_encode($data['repeaterValue']);
            unset($data['repeaterValue']);
        }
        if($data['type'] === 'image') {
            $data['value'] = json_encode($data['fileValue']);
            unset($data['fileValue']);
        }
        if($data['type'] === 'file') {
            $data['value'] = json_encode($data['fileValue']);
            unset($data['fileValue']);
        }
        if($data['type'] === 'string') {
            $data['value'] = json_encode($data['stringValue']);
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
