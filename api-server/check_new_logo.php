<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "CHECKING NEW LOGO SETTING (ID 19)\n";
echo "============================\n\n";

// Check for the logo setting with ID 19
$logoSetting = DB::table('settings')->where('id', 19)->where('key', 'logo')->first();

if ($logoSetting) {
    echo "✅ Logo setting with ID 19 found in database!\n";
    echo "ID: " . $logoSetting->id . "\n";
    echo "Key: " . $logoSetting->key . "\n";
    echo "Value: " . $logoSetting->value . "\n";
    echo "Type: " . $logoSetting->type . "\n";
    echo "Group: " . $logoSetting->group . "\n\n";
    
    // Check if the SettingsController would return this logo
    echo "CHECKING IF CONTROLLER WOULD RETURN THIS LOGO\n";
    echo "-------------------------------------------\n";
    
    // Get the logo that would be returned by the API
    $currentLogoSetting = DB::table('settings')->where('key', 'logo')->first();
    
    if ($currentLogoSetting && $currentLogoSetting->id == 19) {
        echo "✅ The controller WILL return this logo (ID 19) because it's the first/only logo setting\n\n";
    } else {
        echo "❌ The controller will NOT return this logo!\n";
        echo "The controller will return the following logo setting instead:\n";
        if ($currentLogoSetting) {
            echo "ID: " . $currentLogoSetting->id . "\n";
            echo "Key: " . $currentLogoSetting->key . "\n";
            echo "Value: " . $currentLogoSetting->value . "\n\n";
            
            echo "REASON: The API searches for the first record with key='logo', regardless of ID.\n";
            echo "Make sure there is only ONE record with key='logo' in the settings table.\n\n";
        } else {
            echo "No logo setting found with key='logo'.\n\n";
        }
    }
    
    // Test what the controller would actually return
    echo "TESTING CONTROLLER OUTPUT\n";
    echo "----------------------\n";
    $controller = new \App\Http\Controllers\API\SettingsController();
    $response = $controller->getLogo();
    $responseData = json_decode($response->getContent(), true);
    
    echo "Controller response: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n\n";
    
    // Check that the file exists
    echo "CHECKING IF FILE EXISTS\n";
    echo "--------------------\n";
    $filePath = $responseData['image_path'] ?? null;
    $baseFilePath = null;
    
    if ($filePath) {
        // Extract the file path from the value
        if (is_string($logoSetting->value)) {
            // Try to decode it if it's a JSON string
            $decodedValue = json_decode($logoSetting->value, true);
            if ($decodedValue !== null) {
                $baseFilePath = is_array($decodedValue) ? reset($decodedValue) : $decodedValue;
            } else {
                // If it's not JSON, use as is
                $baseFilePath = $logoSetting->value;
            }
            
            // Remove quotes if present
            if (is_string($baseFilePath) && strpos($baseFilePath, '"') === 0 && strrpos($baseFilePath, '"') === strlen($baseFilePath) - 1) {
                $baseFilePath = substr($baseFilePath, 1, -1);
            }
        }
        
        echo "Base file path from setting: " . ($baseFilePath ?? 'unknown') . "\n";
        echo "Full file path from controller: " . $filePath . "\n";
        
        // Check if the file exists in the storage path
        $storagePath = 'storage/app/public/' . ($baseFilePath ?? '');
        if (file_exists($storagePath)) {
            echo "✅ File EXISTS on disk at path: " . $storagePath . "\n";
            
            $fileSize = filesize($storagePath);
            echo "File size: " . formatBytes($fileSize) . "\n";
        } else {
            echo "❌ File does NOT exist on disk at path: " . $storagePath . "\n";
            
            // Check if public storage link is created
            if (!file_exists('public/storage')) {
                echo "⚠️ WARNING: Storage link not created. Run 'php artisan storage:link'\n";
            }
        }
    } else {
        echo "❌ No file path provided in the controller response!\n";
    }
    
    // Check frontend URL
    echo "\nFRONTEND URL CHECK\n";
    echo "----------------\n";
    echo "URL that will be used in the frontend: " . ($responseData['image_url'] ?? 'unknown') . "\n";
    echo "This is the URL that will be visible in your browser's network tab\n";
    
    // Cache busting note
    echo "\nThe frontend adds a timestamp to the URL for cache busting:\n";
    echo $responseData['image_url'] . "?t=" . time() . "\n";
    
} else {
    echo "❌ No logo setting with ID 19 found in the database!\n";
    echo "Please make sure you have added a record with ID 19 and key 'logo' to the settings table.\n";
    
    // Check if any logo setting exists
    $anyLogoSetting = DB::table('settings')->where('key', 'logo')->first();
    if ($anyLogoSetting) {
        echo "\nFound a different logo setting:\n";
        echo "ID: " . $anyLogoSetting->id . "\n";
        echo "Key: " . $anyLogoSetting->key . "\n";
        echo "Value: " . $anyLogoSetting->value . "\n";
    }
}

// Helper function to format bytes
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
} 