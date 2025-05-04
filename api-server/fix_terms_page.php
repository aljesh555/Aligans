<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Find the Terms & Conditions page
$page = DB::table('static_pages')->where('title', 'Terms & Conditions')->first();

if ($page) {
    // Update the slug to the correct format and set it to active
    $updated = DB::table('static_pages')
        ->where('id', $page->id)
        ->update([
            'slug' => 'terms-conditions',
            'is_active' => true
        ]);
    
    if ($updated) {
        echo "Successfully updated Terms & Conditions page:\n";
        echo "- Slug changed to 'terms-conditions'\n";
        echo "- Set to active\n";
    } else {
        echo "Failed to update Terms & Conditions page.\n";
    }
} else {
    echo "Terms & Conditions page not found.\n";
}

// Verify the change
$page = DB::table('static_pages')->where('slug', 'terms-conditions')->first();
if ($page) {
    echo "\nVerification: Terms & Conditions page updated correctly:\n";
    echo "Title: " . $page->title . "\n";
    echo "Slug: " . $page->slug . "\n";
    echo "Active: " . ($page->is_active ? 'Yes' : 'No') . "\n";
} else {
    echo "\nVerification failed: Terms & Conditions page with slug 'terms-conditions' not found.\n";
} 