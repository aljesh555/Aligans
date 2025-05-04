<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Latest Orders';
    
    protected function getTableQuery(): Builder
    {
        // Get latest 5 orders
        return Order::with('user')
            ->latest()
            ->limit(5);
    }
    
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('Order ID')
                ->searchable(),
            
            Tables\Columns\TextColumn::make('user.name')
                ->label('Customer'),
            
            Tables\Columns\TextColumn::make('total_amount')
                ->label('Amount')
                ->money('NPR'),
            
            Tables\Columns\TextColumn::make('payment_method')
                ->label('Payment')
                ->formatStateUsing(fn ($state) => ucfirst($state)),
            
            Tables\Columns\BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'danger' => 'cancelled',
                    'warning' => 'pending',
                    'success' => 'delivered',
                    'primary' => 'processing',
                    'secondary' => 'shipped',
                ])
                ->formatStateUsing(fn ($state) => ucfirst($state)),
            
            Tables\Columns\TextColumn::make('created_at')
                ->label('Date')
                ->dateTime('M d, Y')
                ->sortable(),
        ];
    }
    
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->label('View')
                ->url(fn (Order $record): string => "/apanel/orders/{$record->id}")
                ->icon('heroicon-m-eye')
                ->openUrlInNewTab(),
        ];
    }
} 