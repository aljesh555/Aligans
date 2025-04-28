<?php

// Get database configuration from Laravel's .env file
$env = file_get_contents(__DIR__ . '/.env');
$lines = explode("\n", $env);
$dbConfig = [];

foreach ($lines as $line) {
    if (strpos($line, 'DB_') === 0) {
        list($key, $value) = explode('=', $line, 2);
        $dbConfig[$key] = trim($value);
    }
}

echo "Database configuration:\n";
foreach ($dbConfig as $key => $value) {
    echo "$key: $value\n";
}

// Connect to the database based on the configuration
try {
    $connection = $dbConfig['DB_CONNECTION'] ?? 'mysql';
    $host = $dbConfig['DB_HOST'] ?? '127.0.0.1';
    $port = $dbConfig['DB_PORT'] ?? '3306';
    $database = $dbConfig['DB_DATABASE'] ?? 'laravel';
    $username = $dbConfig['DB_USERNAME'] ?? 'root';
    $password = $dbConfig['DB_PASSWORD'] ?? '';
    
    echo "\nAttempting to connect to database: $connection://$username@$host:$port/$database\n";
    
    if ($connection === 'mysql' || $connection === 'mariadb') {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    } elseif ($connection === 'pgsql') {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$database", $username, $password);
    } elseif ($connection === 'sqlite') {
        $sqlitePath = $database;
        if ($sqlitePath !== ':memory:' && !starts_with($sqlitePath, '/')) {
            $sqlitePath = __DIR__ . '/database/' . $sqlitePath;
        }
        echo "SQLite path: $sqlitePath\n";
        $pdo = new PDO("sqlite:$sqlitePath");
    } else {
        die("Unsupported connection type: $connection");
    }
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected successfully\n";
    
    // Check if terms table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'terms'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "✅ Terms table exists\n";
        
        // Get column information
        $stmt = $pdo->query("DESCRIBE terms");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "\nColumn information:\n";
        foreach ($columns as $column) {
            echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }
        
        // Count records
        $stmt = $pdo->query("SELECT COUNT(*) FROM terms");
        $count = $stmt->fetchColumn();
        echo "\nRecord count: $count\n";
    } else {
        echo "❌ Terms table does NOT exist\n";
    }
    
    // List all tables
    echo "\nAll tables in database:\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
} 