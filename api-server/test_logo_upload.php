<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

echo "TESTING LOGO IMAGE UPLOAD\n";
echo "========================\n\n";

// Sample file path - this would be the file uploaded in the admin
$testImage = 'test_logo.jpg';

// First, check if the logo setting exists
$logoSetting = Setting::where('key', 'logo')->first();

if ($logoSetting) {
    echo "Found existing logo setting in the database:\n";
    echo "ID: " . $logoSetting->id . "\n";
    echo "Key: " . $logoSetting->key . "\n";
    echo "Type: " . $logoSetting->type . "\n";
    echo "Raw value in database: " . json_encode($logoSetting->getRawOriginal('value')) . "\n";
    echo "Decoded value: " . json_encode($logoSetting->value) . "\n\n";
} else {
    echo "No logo setting found in the database.\n\n";
}

// Simulate updating the setting with a new logo
echo "SIMULATING LOGO UPLOAD:\n";
echo "=====================\n";

try {
    // Create a test file if it doesn't exist
    if (!Storage::disk('public')->exists('settings/' . $testImage)) {
        // Create a simple blank image
        $img = imagecreatetruecolor(200, 100);
        $bgColor = imagecolorallocate($img, 255, 255, 255);
        $textColor = imagecolorallocate($img, 0, 0, 0);
        imagefilledrectangle($img, 0, 0, 200, 100, $bgColor);
        imagestring($img, 5, 50, 40, 'Test Logo', $textColor);
        
        // Save it
        ob_start();
        imagejpeg($img);
        $imageData = ob_get_clean();
        Storage::disk('public')->put('settings/' . $testImage, $imageData);
        imagedestroy($img);
        
        echo "Created test image: settings/" . $testImage . "\n";
    } else {
        echo "Using existing test image: settings/" . $testImage . "\n";
    }
    
    // Simulate what Filament does when saving
    if ($logoSetting) {
        // Prepare data like the Filament form would
        $data = [
            'type' => 'image',
            'fileValue' => 'settings/' . $testImage
        ];
        
        // Simulate mutateFormDataBeforeSave
        $jsonEncodedValue = json_encode($data['fileValue']);
        echo "JSON encoded value: " . $jsonEncodedValue . "\n";
        
        // Update the setting
        $logoSetting->value = $jsonEncodedValue;
        $logoSetting->save();
        
        echo "Updated logo setting in database.\n";
    } else {
        // Create a new setting
        $logoSetting = new Setting();
        $logoSetting->key = 'logo';
        $logoSetting->type = 'image';
        $logoSetting->group = 'general';
        $logoSetting->description = 'Site logo';
        $logoSetting->value = json_encode('settings/' . $testImage);
        $logoSetting->save();
        
        echo "Created new logo setting in database.\n";
    }
    
    // Reload the setting to see the saved result
    $updatedSetting = Setting::where('key', 'logo')->first();
    echo "\nSAVED RESULT:\n";
    echo "Raw value in database: " . json_encode($updatedSetting->getRawOriginal('value')) . "\n";
    echo "Decoded value: " . json_encode($updatedSetting->value) . "\n";
    
    // Test if it works with the controller
    echo "\nTESTING CONTROLLER RETRIEVAL:\n";
    $controller = new \App\Http\Controllers\API\SettingsController();
    $response = $controller->getLogo();
    $responseData = json_decode($response->getContent(), true);
    
    echo "Controller response: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
    
    echo "\nVerifying frontend display URL:\n";
    echo "Image URL from controller: " . $responseData['image_url'] . "\n";
    
    // Check if that URL exists
    $imageUrl = str_replace('http://127.0.0.1:8000/', '', $responseData['image_url']);
    if (Storage::disk('public')->exists(str_replace('storage/', '', $imageUrl))) {
        echo "✅ Image file exists and is accessible.\n";
    } else {
        echo "❌ Image file is missing! URL path: " . $imageUrl . "\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 