<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the logo from settings table
$logoSetting = DB::table('settings')->where('key', 'logo')->first();

if ($logoSetting) {
    echo "Logo found in settings table:\n";
    echo "----------------------------\n";
    echo "ID: " . $logoSetting->id . "\n";
    echo "Key: " . $logoSetting->key . "\n";
    echo "Value: " . $logoSetting->value . "\n";
    echo "Type: " . $logoSetting->type . "\n";
    echo "Group: " . $logoSetting->group . "\n";
    echo "Description: " . $logoSetting->description . "\n";
    
    // Display the full URL
    $baseUrl = url('/');
    echo "\nFull logo URL would be: " . $baseUrl . '/storage/' . $logoSetting->value . "\n";
} else {
    echo "No logo found in settings table!\n";
    echo "You may need to add a logo setting with key 'logo'.\n";
} 