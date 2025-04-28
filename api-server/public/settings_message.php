<?php
// Simple script to fetch the message setting directly from the database
// Used as a fallback if the Laravel API isn't working

// Allow cross-origin requests
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Database connection settings (should match your Laravel .env)
$host = 'localhost';
$db = 'sports_management'; // Update this to your actual database name
$user = 'root';            // Update this to your actual database user
$pass = '';                // Update this to your actual database password

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the message setting (ID 14)
    $stmt = $pdo->prepare("SELECT * FROM settings WHERE id = 14 OR `key` = 'message' LIMIT 1");
    $stmt->execute();
    $setting = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($setting) {
        // If the value is stored as JSON, decode it
        if ($setting['value']) {
            $value = $setting['value'];
            // Handle the case where the value is already a JSON string
            if (is_string($value) && substr($value, 0, 1) === '"' && substr($value, -1) === '"') {
                $value = json_decode($value);
            }
            
            // Return just the value
            echo json_encode($value);
        } else {
            echo json_encode("No value found");
        }
    } else {
        echo json_encode("Setting not found");
    }
} catch (PDOException $e) {
    // Return error info
    echo json_encode("Database error: " . $e->getMessage());
} catch (Exception $e) {
    echo json_encode("General error: " . $e->getMessage());
} 