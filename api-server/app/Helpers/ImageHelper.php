<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

/**
 * Helper class for handling images throughout the application
 */
class ImageHelper
{
    /**
     * Store an image file to a specific directory
     *
     * @param UploadedFile|string $file The file to store
     * @param string $directory Directory to store the file in
     * @param string|null $oldImage Path to old image to delete
     * @return string|null The path to the stored file
     */
    public static function storeImage($file, string $directory, ?string $oldImage = null): ?string
    {
        // Delete old image if specified
        if ($oldImage) {
            static::deleteImage($oldImage);
        }

        // Handle empty or null values
        if (empty($file)) {
            return null;
        }

        try {
            // If it's a string path, assume it's already stored
            if (is_string($file)) {
                return $file;
            }

            // If it's a Livewire/Filament temp upload array
            if (is_array($file)) {
                if (count($file) === 1) {
                    // Extract single file from array
                    $file = reset($file);
                } elseif (count($file) > 1) {
                    // Take the first file if multiple
                    $file = $file[0];
                } else {
                    return null;
                }
            }

            // Store the file
            if ($file instanceof UploadedFile) {
                $path = $file->store("public/{$directory}");
                return str_replace('public/', '', $path);
            }

            // Return the original path if it's not something we can store
            return $file;
        } catch (\Exception $e) {
            Log::error("Error storing image: " . $e->getMessage(), [
                'file' => $file,
                'directory' => $directory
            ]);
            return null;
        }
    }

    /**
     * Delete an image from storage
     *
     * @param string|null $path Path to the image
     * @return bool Whether deletion was successful
     */
    public static function deleteImage(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        try {
            // Ensure path starts with the correct disk path
            if (!str_starts_with($path, 'public/')) {
                $path = 'public/' . $path;
            }

            // Delete the file
            return Storage::delete($path);
        } catch (\Exception $e) {
            Log::error("Error deleting image: " . $e->getMessage(), [
                'path' => $path
            ]);
            return false;
        }
    }

    /**
     * Process a raw value to get a clean image path
     * 
     * @param mixed $value The raw value (can be array, string, null)
     * @return string|null The cleaned image path
     */
    public static function processImageValue($value): ?string
    {
        // Handle empty values
        if (empty($value) || $value === 'null' || $value === '""') {
            return null;
        }
        
        // If it's JSON, decode it
        if (is_string($value) && static::isJson($value)) {
            $decoded = json_decode($value, true);
            
            // Handle different JSON structures
            if (is_string($decoded)) {
                return $decoded;
            } elseif (is_array($decoded) && count($decoded) === 1) {
                return reset($decoded);
            } elseif (is_array($decoded) && !empty($decoded)) {
                return $decoded[0];
            }
            
            return null;
        }
        
        // Handle arrays
        if (is_array($value)) {
            if (count($value) === 1) {
                return reset($value);
            } elseif (count($value) > 1) {
                return $value[0];
            }
            
            return null;
        }
        
        // For any other value, return as is
        return $value;
    }
    
    /**
     * Check if a string is valid JSON
     * 
     * @param string $string
     * @return bool
     */
    protected static function isJson($string): bool
    {
        if (!is_string($string)) {
            return false;
        }
        
        try {
            json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        } catch (\Exception $e) {
            return false;
        }
    }
} 