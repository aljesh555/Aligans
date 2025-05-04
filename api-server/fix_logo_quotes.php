<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the logo from settings table
$logoSetting = DB::table('settings')->where('key', 'logo')->first();

if ($logoSetting) {
    echo "Current logo value: " . $logoSetting->value . "\n";
    
    // Check if the value has quotes
    $value = $logoSetting->value;
    if (substr($value, 0, 1) === '"' && substr($value, -1) === '"') {
        // Remove the surrounding quotes
        $cleanValue = substr($value, 1, -1);
        
        echo "Attempting to update value to: " . $cleanValue . "\n";
        
        try {
            // Update the value in the database
            $updated = DB::statement("UPDATE settings SET value = ? WHERE id = ?", [$cleanValue, $logoSetting->id]);
            
            if ($updated) {
                echo "Successfully removed quotes. New value: " . $cleanValue . "\n";
                
                // Check the updated value
                $newSetting = DB::table('settings')->where('key', 'logo')->first();
                echo "Value in database now: " . $newSetting->value . "\n";
            } else {
                echo "Failed to update the value.\n";
            }
        } catch (\Exception $e) {
            echo "Error updating value: " . $e->getMessage() . "\n";
            
            // Try another approach
            echo "Trying alternative approach with JSON_UNQUOTE...\n";
            try {
                $affected = DB::statement(
                    "UPDATE settings SET value = JSON_UNQUOTE(value) WHERE id = ? AND JSON_TYPE(value) = 'STRING'", 
                    [$logoSetting->id]
                );
                
                if ($affected) {
                    $newSetting = DB::table('settings')->where('key', 'logo')->first();
                    echo "Successfully updated with JSON_UNQUOTE. New value: " . $newSetting->value . "\n";
                } else {
                    echo "JSON_UNQUOTE approach also failed.\n";
                }
            } catch (\Exception $e2) {
                echo "Error with JSON_UNQUOTE approach: " . $e2->getMessage() . "\n";
                
                // Last resort: If it's a JSON column, try updating with valid JSON
                echo "Trying with direct JSON approach...\n";
                try {
                    $affected = DB::table('settings')
                        ->where('id', $logoSetting->id)
                        ->update(['value' => json_encode($cleanValue)]);
                    
                    if ($affected) {
                        $newSetting = DB::table('settings')->where('key', 'logo')->first();
                        echo "Successfully updated with JSON encode. New value: " . $newSetting->value . "\n";
                    } else {
                        echo "JSON encode approach also failed.\n";
                    }
                } catch (\Exception $e3) {
                    echo "Error with JSON encode approach: " . $e3->getMessage() . "\n";
                }
            }
        }
    } else {
        echo "The logo value doesn't have surrounding quotes in the expected format. No update needed.\n";
    }
} else {
    echo "No logo found in settings table!\n";
} 