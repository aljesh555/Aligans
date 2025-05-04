<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$page = DB::table('static_pages')->where('slug', 'privacy-policy')->first();

if ($page) {
    echo "Found privacy-policy page:\n";
    echo "Title: " . $page->title . "\n";
    echo "Slug: " . $page->slug . "\n";
    echo "Is Active: " . ($page->is_active ? 'Yes' : 'No') . "\n";
    echo "Content (preview): " . substr($page->content, 0, 50) . "...\n";
} else {
    echo "No privacy-policy page found in the static_pages table.\n";
    
    // List available slugs
    echo "\nAvailable static pages:\n";
    $pages = DB::table('static_pages')->select('id', 'title', 'slug')->get();
    foreach ($pages as $p) {
        echo "- ID: " . $p->id . ", Title: " . $p->title . ", Slug: " . $p->slug . "\n";
    }
} 