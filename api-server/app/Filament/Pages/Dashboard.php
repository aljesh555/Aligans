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
    
    // Header subtitle
    protected function getHeaderSubheading(): ?string
    {
        return 'Monitor your store performance and manage your business efficiently';
    }
} 