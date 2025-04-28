<?php

// This is a Windows-compatible script to check if the terms table exists

// Load Laravel environment
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Check if terms table exists
if (Schema::hasTable('terms')) {
    echo "YES - Terms table exists!\n";
    
    // List all columns in terms table
    $columns = Schema::getColumnListing('terms');
    echo "Table columns: " . implode(', ', $columns) . "\n";
    
    // Get record count
    $count = DB::table('terms')->count();
    echo "Record count: $count\n";
} else {
    echo "NO - Terms table does NOT exist!\n";
}

// List all tables in database
try {
    echo "\nListing all tables:\n";
    $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
    foreach ($tables as $table) {
        echo "- " . $table->name . "\n";
    }
} catch (Exception $e) {
    try {
        // Try MySQL syntax
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $name = current((array)$table);
            echo "- " . $name . "\n";
        }
    } catch (Exception $e) {
        echo "Error listing tables: " . $e->getMessage() . "\n";
    }
} 