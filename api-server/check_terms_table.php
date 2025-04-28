<?php

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check if terms table exists
if (Schema::hasTable('terms')) {
    echo "✅ Terms table exists\n";
    
    // Get column information
    echo "\nColumn information:\n";
    $columns = Schema::getColumnListing('terms');
    echo "Columns: " . implode(', ', $columns) . "\n";
    
    // Count records
    $count = DB::table('terms')->count();
    echo "\nRecord count: {$count}\n";
} else {
    echo "❌ Terms table does NOT exist\n";
}

echo "\nAll tables in database:\n";
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = reset($table);
    echo "- {$tableName}\n";
} 