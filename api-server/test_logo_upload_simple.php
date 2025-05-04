<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Setting;

echo "TESTING LOGO IMAGE UPLOAD\n";
echo "========================\n\n";

// First, check if the logo setting exists
$logoSetting = Setting::where('key', 'logo')->first();

if ($logoSetting) {
    echo "Current logo setting in the database:\n";
    echo "ID: " . $logoSetting->id . "\n";
    echo "Key: " . $logoSetting->key . "\n";
    echo "Type: " . $logoSetting->type . "\n";
    echo "Raw value in database: " . json_encode($logoSetting->getRawOriginal('value')) . "\n";
    echo "Decoded value: " . json_encode($logoSetting->value) . "\n\n";
} else {
    echo "No logo setting found in the database.\n\n";
}

// Test path for a new image - this simulates what would be uploaded in Filament
$testImagePath = "test_logo_image.jpg";

// Simulate updating the setting with a new logo
echo "SIMULATING LOGO UPLOAD:\n";
echo "=====================\n";

try {
    // Simulate what Filament does when saving
    if ($logoSetting) {
        // Prepare data like the Filament form would
        $data = [
            'type' => 'image',
            'fileValue' => $testImagePath
        ];
        
        // Simulate mutateFormDataBeforeSave
        $jsonEncodedValue = json_encode($data['fileValue']);
        echo "JSON encoded value: " . $jsonEncodedValue . "\n";
        
        // Update the setting
        $logoSetting->type = 'image';
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
        $logoSetting->value = json_encode($testImagePath);
        $logoSetting->save();
        
        echo "Created new logo setting in database.\n";
    }
    
    // Reload the setting to see the saved result
    $updatedSetting = Setting::where('key', 'logo')->first()->fresh();
    echo "\nSAVED RESULT:\n";
    echo "Raw value in database: " . json_encode($updatedSetting->getRawOriginal('value')) . "\n";
    echo "Decoded value: " . json_encode($updatedSetting->value) . "\n";
    
    // Test if it works with the controller
    echo "\nTESTING CONTROLLER RETRIEVAL:\n";
    $controller = new \App\Http\Controllers\API\SettingsController();
    $response = $controller->getLogo();
    $responseData = json_decode($response->getContent(), true);
    
    echo "Controller response: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
    
    echo "\nSUMMARY:\n";
    echo "1. Original raw value: " . json_encode($logoSetting->getRawOriginal('value')) . "\n";
    echo "2. New raw value after save: " . json_encode($updatedSetting->getRawOriginal('value')) . "\n";
    echo "3. Decoded value from getLogo(): " . $responseData['image_path'] . "\n";
    echo "4. URL constructed for frontend: " . $responseData['image_url'] . "\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 