<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$static_pages = DB::table('static_pages')->get();

echo "Static Pages Table Contents:\n";
echo "===========================\n";

if ($static_pages->isEmpty()) {
    echo "No records found in the static_pages table.\n";
} else {
    foreach ($static_pages as $page) {
        echo "ID: " . $page->id . "\n";
        echo "Title: " . $page->title . "\n";
        echo "Slug: " . $page->slug . "\n";
        echo "Content: " . substr($page->content, 0, 100) . "...\n";
        echo "Created: " . $page->created_at . "\n";
        echo "Updated: " . $page->updated_at . "\n";
        echo "----------------------------\n";
    }
} 