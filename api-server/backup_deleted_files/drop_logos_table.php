<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    // Check if the logos table exists
    if (Schema::hasTable('logos')) {
        echo "Found 'logos' table in the database.\n";
        
        // Get the count of logos
        $count = DB::table('logos')->count();
        echo "The table contains {$count} logo records.\n";
        
        // Confirm logo is stored in settings table before proceeding
        $settingLogo = DB::table('settings')->where('key', 'logo')->first();
        
        if ($settingLogo) {
            echo "Logo found in settings table with value: {$settingLogo->value}\n";
            
            // Drop the table
            Schema::dropIfExists('logos');
            echo "Successfully dropped the 'logos' table.\n";
            
            echo "The logo is now managed through the settings table with key 'logo'.\n";
        } else {
            echo "WARNING: Logo not found in settings table! Please add it before dropping the logos table.\n";
            echo "You can add it manually by running:\n";
            echo "INSERT INTO settings (key, value, type, group, description) VALUES ('logo', 'YOUR_LOGO_FILENAME', 'string', 'general', 'Site logo');\n";
        }
    } else {
        echo "The 'logos' table does not exist in the database.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 