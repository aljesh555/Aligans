<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// List all static pages
echo "All static pages in the database:\n";
echo "================================\n";
$pages = DB::table('static_pages')->select('id', 'title', 'slug', 'is_active')->get();

foreach ($pages as $p) {
    echo "- ID: {$p->id}, Title: {$p->title}, Slug: {$p->slug}, Active: " . ($p->is_active ? 'Yes' : 'No') . "\n";
}

// Check for terms-related pages
echo "\nSearching for terms-related pages:\n";
echo "================================\n";

$termsPages = DB::table('static_pages')
    ->where('slug', 'like', '%term%')
    ->orWhere('title', 'like', '%Term%')
    ->get();

if ($termsPages->count() > 0) {
    foreach ($termsPages as $page) {
        echo "Found terms-related page:\n";
        echo "ID: {$page->id}\n";
        echo "Title: {$page->title}\n";
        echo "Slug: {$page->slug}\n";
        echo "Active: " . ($page->is_active ? 'Yes' : 'No') . "\n";
        echo "Content (preview): " . substr($page->content, 0, 50) . "...\n\n";
    }
} else {
    echo "No terms-related pages found.\n";
} 