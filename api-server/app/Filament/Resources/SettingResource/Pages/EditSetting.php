<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\Concerns\FixesImageUploads;

class EditSetting extends EditRecord
{
    use FixesImageUploads;
    
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
                    
                    // Handle empty values
                    if (empty($decoded) || $decoded === 'null' || $decoded === '""') {
                        $data['fileValue'] = null;
                    }
                    // Handle string values (simple path)
                    else if (is_string($decoded)) {
                        $data['fileValue'] = $decoded;
                    } 
                    // Handle array with a single value
                    else if (is_array($decoded) && count($decoded) === 1) {
                        $data['fileValue'] = reset($decoded);
                    }
                    // Handle array of values (multiple uploads)
                    else if (is_array($decoded)) {
                        $data['fileValue'] = $decoded;
                    }
                    // Default to original value if all else fails
                    else {
                        $data['fileValue'] = $data['value'];
                    }
                } catch (\Exception $e) {
                    // If JSON decoding fails, try the raw value
                    if (!empty($data['value']) && $data['value'] !== 'null' && $data['value'] !== '""') {
                        $data['fileValue'] = $data['value'];
                    } else {
                        $data['fileValue'] = null;
                    }
                }
            } 
            // If already not a string (possibly already processed)
            else if (!empty($data['value'])) {
                $data['fileValue'] = $data['value'];
            }
            else {
                $data['fileValue'] = null;
            }
            
            // Remove original value to avoid confusion
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
            // Handle cases where fileValue might be null or empty
            if (empty($data['fileValue'])) {
                $data['value'] = null;
            } else {
                // Force to string if it's a new upload (might be an array)
                if (is_array($data['fileValue']) && count($data['fileValue']) === 1) {
                    $data['value'] = json_encode(reset($data['fileValue']));
                } else {
                    $data['value'] = json_encode($data['fileValue']);
                }
                
                // Debugging output
                \Illuminate\Support\Facades\Log::info('Saving image value: ' . $data['value']);
            }
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
        $actions = [
            Actions\DeleteAction::make(),
        ];
        
        // Add a special action for updating any image
        if ($this->getRecord() && $this->getRecord()->type === 'image') {
            $actions[] = Action::make('replaceImage')
                ->label('Replace Image')
                ->icon('heroicon-o-photo')
                ->color('primary')
                ->form([
                    FileUpload::make('new_image')
                        ->image()
                        ->required()
                        ->disk('public')
                        ->directory('settings')
                        ->visibility('public')
                        ->maxSize(2048)
                ])
                ->action(function (array $data): void {
                    $record = $this->getRecord();
                    
                    // Log what's happening
                    \Illuminate\Support\Facades\Log::info('Replacing image', [
                        'key' => $record->key,
                        'old_value' => $record->value,
                        'new_image' => $data['new_image']
                    ]);
                    
                    // Delete old image if exists
                    if (!empty($record->value)) {
                        try {
                            $oldImage = json_decode($record->value, true);
                            if (!empty($oldImage) && is_string($oldImage)) {
                                Storage::disk('public')->delete($oldImage);
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Error deleting old image: ' . $e->getMessage());
                        }
                    }
                    
                    // Save new image directly as a JSON string
                    if (is_array($data['new_image'])) {
                        $record->value = json_encode(reset($data['new_image']));
                    } else {
                        $record->value = json_encode($data['new_image']);
                    }
                    
                    $record->save();
                    
                    // Force reload the page to clear any stale UI state
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $record]));
                });
                
            // Add a clear logo action
            $actions[] = Action::make('clearImage')
                ->label('Clear Image')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function(): void {
                    $record = $this->getRecord();
                    
                    // Delete the image file
                    if (!empty($record->value)) {
                        try {
                            $oldImage = json_decode($record->value, true);
                            if (!empty($oldImage) && is_string($oldImage)) {
                                Storage::disk('public')->delete($oldImage);
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Error deleting image: ' . $e->getMessage());
                        }
                    }
                    
                    // Clear the value
                    $record->value = null;
                    $record->save();
                    
                    // Force reload the page
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $record]));
                });
        }
        
        return $actions;
    }
}
