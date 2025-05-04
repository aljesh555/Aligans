<?php
// List of files to be deleted
$filesToDelete = [
    // Model
    __DIR__ . '/app/Models/Logo.php',
    
    // Controller
    __DIR__ . '/app/Http/Controllers/Api/LogoController.php',
    
    // Migrations
    __DIR__ . '/database/migrations/2025_04_17_000000_create_logos_table.php',
    __DIR__ . '/database/migrations/2025_04_17_132918_create_logos_table.php',
    __DIR__ . '/database/migrations/2025_04_17_122137_add_image_column_to_logos.php',
    __DIR__ . '/database/migrations/2025_04_17_125659_drop_logos_table.php',
    __DIR__ . '/database/migrations/2025_04_17_143248_drop_logos_table.php',
    
    // Seeder
    __DIR__ . '/database/seeders/LogoSeeder.php',
    
    // Other related files
    __DIR__ . '/activate_logo.php',
    __DIR__ . '/drop_logos_table.php',
];

echo "Attempting to delete the following logo-related files:\n";
echo "=================================================\n";

foreach ($filesToDelete as $file) {
    echo "Checking file: $file\n";
    
    if (file_exists($file)) {
        // Backup the file before deleting (for safety)
        $backupDir = __DIR__ . '/backup_deleted_files';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        $fileName = basename($file);
        $backupPath = $backupDir . '/' . $fileName;
        
        if (copy($file, $backupPath)) {
            echo "Backed up to: $backupPath\n";
            
            // Delete the file
            if (unlink($file)) {
                echo "Successfully deleted: $file\n";
            } else {
                echo "Failed to delete: $file\n";
            }
        } else {
            echo "Failed to backup, not deleting: $file\n";
        }
    } else {
        echo "File not found: $file\n";
    }
    
    echo "---\n";
}

echo "\nAll files processed. Deleted files are backed up in " . __DIR__ . "/backup_deleted_files\n";
echo "You can review the backup directory and delete it if everything is working correctly.\n"; 