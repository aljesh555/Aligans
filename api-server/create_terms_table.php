<?php

// Bootstrap the Laravel application
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Import necessary facades
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

echo "Starting terms table creation...\n";

// Drop the table if it exists
if (Schema::hasTable('terms')) {
    Schema::drop('terms');
    echo "Dropped existing terms table.\n";
}

// Create the terms table
Schema::create('terms', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->date('last_updated');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

echo "Terms table created successfully!\n";

// Verify the table exists
if (Schema::hasTable('terms')) {
    echo "Verification: Terms table exists.\n";
    
    // List columns
    $columns = Schema::getColumnListing('terms');
    echo "Columns: " . implode(', ', $columns) . "\n";
} else {
    echo "ERROR: Failed to create terms table!\n";
}

// Insert an initial record
DB::table('terms')->insert([
    'title' => 'Terms & Conditions',
    'content' => '<h2>Welcome to Our Terms & Conditions</h2><p>This is a placeholder for your terms and conditions content.</p>',
    'last_updated' => now(),
    'is_active' => true,
    'created_at' => now(),
    'updated_at' => now()
]);

echo "Initial record inserted.\n";

// Count records
$count = DB::table('terms')->count();
echo "Record count: $count\n"; 