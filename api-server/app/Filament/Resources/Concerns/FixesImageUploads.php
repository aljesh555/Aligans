<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Forms;

trait FixesImageUploads
{
    /**
     * Configure a FileUpload component to work properly with image uploads
     * 
     * @param FileUpload $component
     * @param string $directory
     * @param array $options
     * @return FileUpload
     */
    public static function fixImageUpload(FileUpload $component, string $directory = 'images', array $options = []): FileUpload
    {
        // Log component configuration for debugging
        \Illuminate\Support\Facades\Log::info("Configuring FileUpload component", [
            'component' => $component->getName(),
            'directory' => $directory
        ]);
        
        // Basic configuration that should work with any Filament version
        $component->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxSize(5120); // 5MB
        
        // These methods are safer and available in most versions
        try {
            if (method_exists($component, 'enableOpen')) {
                $component->enableOpen();
            }
            
            if (method_exists($component, 'enableDownload')) {
                $component->enableDownload();
            }
            
            // IMPORTANT: For Filament 2.x use panelAspectRatio instead of imageCropAspectRatio
            if (method_exists($component, 'panelAspectRatio')) {
                $component->panelAspectRatio('16:9');
            }
            
            // Set maximum upload time to avoid timeouts
            if (method_exists($component, 'maxSize')) {
                $component->maxSize(2048); // 2MB - smaller to avoid timeouts
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error configuring FileUpload component: " . $e->getMessage());
        }
        
        return $component;
    }
    
    /**
     * Create a replace image action for any record
     * 
     * @param string $fieldName
     * @param string $directory
     * @param string $actionLabel
     * @return Action
     */
    public static function createReplaceImageAction(string $fieldName, string $directory = 'images', string $actionLabel = 'Replace Image'): Action
    {
        // Log action creation for debugging
        \Illuminate\Support\Facades\Log::info("Creating replace image action", [
            'field' => $fieldName,
            'directory' => $directory,
            'label' => $actionLabel
        ]);
        
        return Action::make('replace_' . $fieldName)
            ->label($actionLabel)
            ->form([
                Forms\Components\FileUpload::make('new_image')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory($directory)
                    ->visibility('public')
                    ->maxSize(2048) // Reduced size to avoid timeouts
            ])
            ->action(function (array $data, $record) use ($fieldName): void {
                // Log start of action for debugging
                \Illuminate\Support\Facades\Log::info("Starting replace image action", [
                    'record_id' => $record->id,
                    'field' => $fieldName,
                    'has_new_image' => !empty($data['new_image'])
                ]);
                
                // Delete old image if exists
                if (!empty($record->{$fieldName})) {
                    try {
                        // Log old image information
                        \Illuminate\Support\Facades\Log::info("Deleting old image", [
                            'old_image' => $record->{$fieldName},
                        ]);
                        
                        Storage::disk('public')->delete($record->{$fieldName});
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Error deleting old image from {$fieldName}: " . $e->getMessage(), [
                            'exception' => $e,
                            'record_id' => $record->id
                        ]);
                    }
                }
                
                // Save new image
                $record->{$fieldName} = $data['new_image'];
                $record->save();
                
                // Log success
                \Illuminate\Support\Facades\Log::info("Successfully replaced image in {$fieldName} for record {$record->id}", [
                    'new_image' => $data['new_image']
                ]);
                
                // Force a redirect to refresh the page completely
                redirect()->to(request()->header('Referer'));
            });
    }
    
    /**
     * Create a clear image action for any record
     * 
     * @param string $fieldName
     * @param string $actionLabel
     * @return Action
     */
    public static function createClearImageAction(string $fieldName, string $actionLabel = 'Clear Image'): Action
    {
        // Log action creation for debugging
        \Illuminate\Support\Facades\Log::info("Creating clear image action", [
            'field' => $fieldName,
            'label' => $actionLabel
        ]);
        
        return Action::make('clear_' . $fieldName)
            ->label($actionLabel)
            ->color('danger')
            ->requiresConfirmation()
            ->action(function ($record) use ($fieldName): void {
                // Log start of action for debugging
                \Illuminate\Support\Facades\Log::info("Starting clear image action", [
                    'record_id' => $record->id,
                    'field' => $fieldName
                ]);
                
                // Delete the image file
                if (!empty($record->{$fieldName})) {
                    try {
                        // Log image information being deleted
                        \Illuminate\Support\Facades\Log::info("Deleting image", [
                            'image' => $record->{$fieldName},
                        ]);
                        
                        Storage::disk('public')->delete($record->{$fieldName});
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Error deleting image from {$fieldName}: " . $e->getMessage(), [
                            'exception' => $e,
                            'record_id' => $record->id
                        ]);
                    }
                }
                
                // Clear the value
                $record->{$fieldName} = null;
                $record->save();
                
                // Log success
                \Illuminate\Support\Facades\Log::info("Successfully cleared image in {$fieldName} for record {$record->id}");
                
                // Force a redirect to refresh the page completely
                redirect()->to(request()->header('Referer'));
            });
    }
} 