<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Logo;

// Find the logo
$logo = Logo::find(1);

if ($logo) {
    // Use the setActive method to make this logo active
    Logo::setActive($logo->id);
    
    echo "Logo ID {$logo->id} has been set as active.\n";
    
    // Check if base64_image is NULL and generate it if needed
    if (empty($logo->base64_image) && !empty($logo->image_path)) {
        $path = str_replace('storage/', '', $logo->image_path);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            $content = \Illuminate\Support\Facades\Storage::disk('public')->get($path);
            $mime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($path);
            
            // Convert to base64
            $base64 = 'data:' . $mime . ';base64,' . base64_encode($content);
            
            // Update the logo with base64 image
            $logo->update([
                'base64_image' => $base64
            ]);
            
            echo "Base64 image has been generated and saved.\n";
        } else {
            echo "Warning: Image file not found at {$path}\n";
        }
    }
} else {
    echo "Logo with ID 1 not found.\n";
} 