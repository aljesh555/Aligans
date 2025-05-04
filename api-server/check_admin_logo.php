<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

echo "CHECKING ADMIN-INSERTED LOGO IN SETTINGS TABLE\n";
echo "===========================================\n\n";

// Check if the logo setting exists
$logoSetting = Setting::where('key', 'logo')->first();

if (!$logoSetting) {
    echo "⚠️ No logo setting found in the database.\n";
    exit;
}

echo "CURRENT LOGO SETTING:\n";
echo "-----------------\n";
echo "ID: " . $logoSetting->id . "\n";
echo "Key: " . $logoSetting->key . "\n";
echo "Type: " . $logoSetting->type . "\n";
echo "Group: " . $logoSetting->group . "\n";
echo "Description: " . $logoSetting->description . "\n";
echo "Raw value in database: " . json_encode($logoSetting->getRawOriginal('value')) . "\n";
echo "Decoded value: " . json_encode($logoSetting->value) . "\n\n";

// Check if the value is properly JSON formatted
$rawValue = $logoSetting->getRawOriginal('value');
echo "JSON VALIDATION:\n";
echo "--------------\n";
json_decode($rawValue);
$jsonError = json_last_error();
if ($jsonError === JSON_ERROR_NONE) {
    echo "✅ The value is properly JSON formatted.\n";
} else {
    echo "❌ The value is NOT properly JSON formatted. Error: " . json_last_error_msg() . "\n";
}

// Check if the file exists in storage
echo "\nFILE VERIFICATION:\n";
echo "----------------\n";
$decodedValue = json_decode($rawValue, true);
if (is_string($decodedValue)) {
    $filePath = $decodedValue;
    echo "File path from setting: " . $filePath . "\n";
    
    if (Storage::disk('public')->exists($filePath)) {
        echo "✅ File exists in storage at path: " . $filePath . "\n";
        
        // Get file info
        $size = Storage::disk('public')->size($filePath);
        $lastModified = Storage::disk('public')->lastModified($filePath);
        
        echo "File size: " . formatBytes($size) . "\n";
        echo "Last modified: " . date('Y-m-d H:i:s', $lastModified) . "\n";
    } else {
        echo "❌ File does NOT exist in storage at path: " . $filePath . "\n";
        
        // Try checking without the "settings/" prefix
        if (strpos($filePath, 'settings/') === 0) {
            $alternativePath = substr($filePath, 9); // Remove "settings/"
            if (Storage::disk('public')->exists($alternativePath)) {
                echo "✅ However, file DOES exist at alternative path: " . $alternativePath . "\n";
            }
        }
        
        // List the contents of the settings directory for comparison
        echo "\nContents of storage/settings directory:\n";
        $files = Storage::disk('public')->files('settings');
        foreach ($files as $file) {
            echo "- " . $file . "\n";
        }
    }
} else {
    echo "❓ The decoded value is not a string. Value: " . json_encode($decodedValue) . "\n";
}

// Check how the controller would handle this
echo "\nCONTROLLER RESPONSE TEST:\n";
echo "----------------------\n";
$controller = new \App\Http\Controllers\API\SettingsController();
$response = $controller->getLogo();
$responseData = json_decode($response->getContent(), true);

echo "Controller response: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
echo "Image path from controller: " . $responseData['image_path'] . "\n";
echo "Image URL from controller: " . $responseData['image_url'] . "\n";

// Try to access the full URL
$imageUrl = str_replace(['http://localhost/', 'http://127.0.0.1:8000/'], '', $responseData['image_url']);
echo "\nFull image path would be: " . public_path($imageUrl) . "\n";

// Function to format bytes to human-readable format
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
} 