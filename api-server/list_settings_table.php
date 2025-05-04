<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Setting;

echo "ALL SETTINGS IN DATABASE\n";
echo "======================\n\n";

$settings = Setting::all();

if ($settings->isEmpty()) {
    echo "No settings found in the database.\n";
    exit;
}

echo "Found " . $settings->count() . " settings:\n\n";

foreach ($settings as $index => $setting) {
    echo "SETTING #" . ($index + 1) . ":\n";
    echo "----------" . str_repeat('-', strlen((string)($index + 1))) . "\n";
    echo "ID: " . $setting->id . "\n";
    echo "Key: " . $setting->key . "\n";
    echo "Type: " . $setting->type . "\n";
    echo "Group: " . $setting->group . "\n";
    
    // Format the output depending on the type
    echo "Value: ";
    if ($setting->type === 'image' || $setting->type === 'file') {
        // For images/files, just show a summary
        if (is_array($setting->value)) {
            echo "[File reference - " . json_encode($setting->value) . "]\n";
        } else {
            echo "[File reference - " . $setting->value . "]\n";
        }
    } else if ($setting->type === 'repeater' && is_array($setting->value)) {
        // For repeater type, show in a more readable format
        echo "\n";
        foreach ($setting->value as $item) {
            if (isset($item['key']) && isset($item['value'])) {
                echo "  - " . $item['key'] . ": " . $item['value'] . "\n";
            } else {
                echo "  - " . json_encode($item) . "\n";
            }
        }
    } else {
        // For other types, show the value
        echo json_encode($setting->value, JSON_PRETTY_PRINT) . "\n";
    }
    
    echo "Description: " . ($setting->description ?: 'N/A') . "\n";
    echo "Created: " . $setting->created_at . "\n";
    echo "Updated: " . $setting->updated_at . "\n";
    echo "\n";
} 