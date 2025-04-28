<?php
// This is a simple script to fix social media links in the database

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;

// Get existing social links
$current = Setting::where('key', 'social_links')->first();
if ($current) {
    echo "Current social links: " . json_encode($current->value, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "No social links found in database\n";
}

// Update or create social links
$socialLinks = [
    'facebook' => 'https://www.facebook.com/aljesh.raut',
    'instagram' => 'https://www.instagram.com/aljeshraut/',
    'twitter' => 'https://twitter.com',
    'youtube' => 'https://youtube.com'
];

Setting::set('social_links', $socialLinks, 'json', 'social', 'Social media links');

// Verify the update
$updated = Setting::where('key', 'social_links')->first();
echo "Updated social links: " . json_encode($updated->value, JSON_PRETTY_PRINT) . "\n";

echo "Social links have been fixed. Please refresh your frontend.\n"; 