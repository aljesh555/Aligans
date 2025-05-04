<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Find the Privacy Policy page with incorrect slug
$page = DB::table('static_pages')->where('title', 'Privacy Policy')->first();

if ($page) {
    // Update the slug to the correct format
    $updated = DB::table('static_pages')
        ->where('id', $page->id)
        ->update(['slug' => 'privacy-policy']);
    
    if ($updated) {
        echo "Successfully updated Privacy Policy page slug to 'privacy-policy'.\n";
    } else {
        echo "Failed to update Privacy Policy page slug.\n";
    }
} else {
    echo "Privacy Policy page not found.\n";
}

// Verify the change
$page = DB::table('static_pages')->where('slug', 'privacy-policy')->first();
if ($page) {
    echo "\nVerification: Privacy Policy page now has correct slug:\n";
    echo "Title: " . $page->title . "\n";
    echo "Slug: " . $page->slug . "\n";
} else {
    echo "\nVerification failed: Privacy Policy page with slug 'privacy-policy' still not found.\n";
} 