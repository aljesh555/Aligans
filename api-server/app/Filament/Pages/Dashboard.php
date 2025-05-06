<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    // Dashboard title
    protected static ?string $title = 'Aligans Sports Dashboard';
    
    // Override the default dashboard slug to ensure only one appears
    protected static ?string $slug = 'dashboard';
    
    // Override the navigation label to ensure consistency
    protected static ?string $navigationLabel = 'Dashboard';
    
    // Set as the first navigation item
    protected static ?int $navigationSort = -2;
    
    // Override the navigation group to ensure it's in the root level
    protected static ?string $navigationGroup = null;
    
    // Header subtitle
    protected function getHeaderSubheading(): ?string
    {
        return 'Monitor your store performance and manage your business efficiently';
    }
} 