<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    
    protected function getStats(): array
    {
        // Get total sales amount
        $totalSales = Order::where('status', 'delivered')
            ->sum('total_amount');
            
        // Get orders count
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Get total products
        $totalProducts = Product::count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        
        // Get registered users
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        return [
            Stat::make('Total Sales', 'Rs. ' . number_format($totalSales, 2))
                ->description('Overall sales revenue')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
                
            Stat::make('Pending Orders', $pendingOrders)
                ->description('Orders awaiting processing')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
                
            Stat::make('Products', $totalProducts)
                ->description($outOfStockProducts . ' out of stock')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color($outOfStockProducts > 0 ? 'warning' : 'success'),
                
            Stat::make('Customers', $totalUsers)
                ->description($newUsersThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
        ];
    }
} 