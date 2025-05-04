<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockProductsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Low Stock Products';
    
    protected function getTableQuery(): Builder
    {
        // Get products with stock less than or equal to 5
        return Product::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->latest()
            ->limit(5);
    }
    
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
            
            Tables\Columns\TextColumn::make('name')
                ->label('Product')
                ->searchable()
                ->limit(30),
            
            Tables\Columns\TextColumn::make('category.name')
                ->label('Category'),
            
            Tables\Columns\TextColumn::make('price')
                ->label('Price')
                ->money('NPR'),
            
            Tables\Columns\TextColumn::make('stock')
                ->label('Stock')
                ->sortable()
                ->formatStateUsing(fn ($state) => "{$state} units")
                ->color('danger'),
        ];
    }
    
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('edit')
                ->label('Edit')
                ->url(fn (Product $record): string => "/apanel/products/{$record->id}/edit")
                ->icon('heroicon-m-pencil')
                ->openUrlInNewTab(),
        ];
    }
    
    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-check-circle';
    }
    
    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No low stock products';
    }
    
    protected function getTableEmptyStateDescription(): ?string
    {
        return 'All your products have sufficient stock levels.';
    }
} 