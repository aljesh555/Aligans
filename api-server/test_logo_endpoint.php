<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Initialize a cURL session to test the endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/api/settings/logo');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status Code: " . $httpCode . "\n\n";
echo "Raw Response:\n";
echo $response . "\n\n";

// Try to decode the JSON
$jsonData = json_decode($response, true);
if ($jsonData) {
    echo "Decoded JSON Response:\n";
    print_r($jsonData);
    
    // Check for specific fields
    if (isset($jsonData['image_path'])) {
        echo "\nImage Path: " . $jsonData['image_path'] . "\n";
        
        // Check if there are quotes in the image path
        if (strpos($jsonData['image_path'], '"') !== false) {
            echo "WARNING: Image path contains quote characters which may cause display issues!\n";
        }
    }
    
    if (isset($jsonData['image_url'])) {
        echo "Image URL: " . $jsonData['image_url'] . "\n";
        
        // Check if there are quotes in the image URL
        if (strpos($jsonData['image_url'], '"') !== false) {
            echo "WARNING: Image URL contains quote characters which may cause display issues!\n";
        }
    }
} else {
    echo "Failed to decode JSON response\n";
} 