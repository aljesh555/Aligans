<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check settings table structure
echo "SETTINGS TABLE STRUCTURE:\n";
echo "=======================\n";

$columns = DB::select('SHOW COLUMNS FROM settings');
foreach ($columns as $column) {
    echo "{$column->Field}: {$column->Type}";
    if ($column->Null === 'NO') echo " NOT NULL";
    if ($column->Key) echo " [{$column->Key}]";
    if ($column->Default !== null) echo " DEFAULT '{$column->Default}'";
    echo "\n";
}

// Check table constraints
echo "\nTABLE CONSTRAINTS:\n";
echo "=================\n";
$constraints = DB::select("
    SELECT 
        tc.CONSTRAINT_NAME, 
        tc.CONSTRAINT_TYPE,
        kcu.COLUMN_NAME,
        rc.UPDATE_RULE,
        rc.DELETE_RULE
    FROM information_schema.TABLE_CONSTRAINTS tc
    LEFT JOIN information_schema.KEY_COLUMN_USAGE kcu 
        ON tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
    LEFT JOIN information_schema.REFERENTIAL_CONSTRAINTS rc
        ON tc.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
    WHERE tc.TABLE_NAME = 'settings'
");

foreach ($constraints as $constraint) {
    echo "Constraint: {$constraint->CONSTRAINT_NAME}, Type: {$constraint->CONSTRAINT_TYPE}, Column: {$constraint->COLUMN_NAME}\n";
}

// Check for JSON columns
echo "\nCHECKING VALUE COLUMN TYPE:\n";
echo "========================\n";
$valueColumn = collect($columns)->where('Field', 'value')->first();
echo "Value column type: {$valueColumn->Type}\n";

if (strpos(strtolower($valueColumn->Type), 'json') !== false) {
    echo "The value column appears to be a JSON type column.\n";
    echo "For JSON columns, data must be valid JSON or it will cause constraint violations.\n";
} else {
    echo "The value column is not explicitly a JSON type.\n";
}

// Check the Setting model to see how it handles the value column
echo "\nCHECKING SETTING MODEL:\n";
echo "====================\n";
$settingModel = app(\App\Models\Setting::class);
$casts = $settingModel->getCasts();
echo "Model casts: " . json_encode($casts) . "\n";

// Show samples of working values
echo "\nSAMPLE VALUES IN SETTINGS TABLE:\n";
echo "============================\n";
$sampleSettings = DB::table('settings')->limit(3)->get();
foreach ($sampleSettings as $sample) {
    echo "ID: {$sample->id}, Key: {$sample->key}, Type: {$sample->type}\n";
    echo "Raw Value: " . json_encode($sample->value) . "\n\n";
} 