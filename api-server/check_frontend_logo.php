<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "CHECKING FRONTEND LOGO DISPLAY\n";
echo "===========================\n\n";

// Show the value in the settings table
$setting = DB::table('settings')->where('key', 'logo')->first();
echo "Raw logo value in database: " . $setting->value . "\n\n";

// Calculate what would be sent to the frontend
$controller = new \App\Http\Controllers\API\SettingsController();
$response = $controller->getLogo();
$responseData = json_decode($response->getContent(), true);

echo "Response to frontend: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n\n";

echo "Checking frontend display in Header.vue:\n";
echo "--------------------------------------\n";
echo "In Header.vue, line 734: const response = await axios.get('http://localhost:8000/api/settings/logo');\n";
echo "This will get the data shown above\n\n";

echo "Image path: " . $responseData['image_path'] . "\n";
echo "Image URL: " . $responseData['image_url'] . "\n\n";

echo "Checking the actual file:\n";
echo "----------------------\n";
$filePath = 'storage/app/public/' . $responseData['image_path'];
if (file_exists($filePath)) {
    $size = filesize($filePath);
    echo "✅ File EXISTS with size: " . formatSize($size) . "\n";
} else {
    echo "❌ File does NOT exist at path: " . $filePath . "\n";
    
    // Check if the public disk is linked to storage
    if (file_exists('public/storage')) {
        echo "Public disk IS linked to storage\n";
    } else {
        echo "Public disk is NOT linked to storage - run 'php artisan storage:link'\n";
    }
}

function formatSize($size, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $size = max($size, 0);
    $pow = floor(($size ? log($size) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $size /= (1 << (10 * $pow));
    return round($size, $precision) . ' ' . $units[$pow];
} 