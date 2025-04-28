<?php

require __DIR__ . '/../vendor/autoload.php';

echo "Testing class loading...\n";

try {
    // Load Logo model
    $logoModelClass = \App\Models\Logo::class;
    echo "Logo model class: $logoModelClass\n";
    
    // Load LogoResource
    $resourceClass = \App\Filament\Resources\LogoResource::class;
    echo "Logo resource class: $resourceClass\n";
    
    // Load pages
    $listClass = \App\Filament\Resources\LogoResource\Pages\ListLogos::class;
    echo "List Logos class: $listClass\n";
    
    $createClass = \App\Filament\Resources\LogoResource\Pages\CreateLogo::class;
    echo "Create Logo class: $createClass\n";
    
    $editClass = \App\Filament\Resources\LogoResource\Pages\EditLogo::class;
    echo "Edit Logo class: $editClass\n";
    
    echo "All classes loaded successfully!\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
} 