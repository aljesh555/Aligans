<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Order Statistics';
    
    protected static ?string $pollingInterval = '60s';
    
    protected int | string | array $columnSpan = 'full';
    
    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last 7 days',
            'month' => 'Last 30 days',
            'year' => 'This year',
        ];
    }
    
    protected function getData(): array
    {
        $filter = $this->filter ?? 'month';
        
        $startDate = match($filter) {
            'week' => now()->subDays(7),
            'month' => now()->subDays(30),
            'year' => now()->startOfYear(),
            default => now()->subDays(30),
        };
        
        $ordersByDate = match($filter) {
            'week' => $this->getOrdersByDay($startDate),
            'month' => $this->getOrdersByDay($startDate),
            'year' => $this->getOrdersByMonth($startDate),
            default => $this->getOrdersByDay($startDate),
        };
        
        $labels = array_keys($ordersByDate);
        
        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => array_values($ordersByDate),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    protected function getType(): string
    {
        return 'bar';
    }
    
    private function getOrdersByDay(Carbon $startDate): array
    {
        $endDate = now();
        $days = [];
        
        $orders = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->count;
            })
            ->toArray();
        
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');
            $formattedDate = $date->format('M d');
            $days[$formattedDate] = $orders[$dateKey] ?? 0;
        }
        
        return $days;
    }
    
    private function getOrdersByMonth(Carbon $startDate): array
    {
        $endDate = now();
        $months = [];
        
        $orders = Order::select(
                DB::raw('YEAR(created_at) as year'), 
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->get()
            ->map(function ($item) {
                $date = Carbon::createFromDate($item->year, $item->month, 1);
                return [
                    'date' => $date->format('Y-m'),
                    'count' => $item->count,
                ];
            })
            ->keyBy('date')
            ->map(function ($item) {
                return $item['count'];
            })
            ->toArray();
        
        for ($date = clone $startDate; $date->lte($endDate); $date->addMonth()) {
            $dateKey = $date->format('Y-m');
            $formattedDate = $date->format('M Y');
            $months[$formattedDate] = $orders[$dateKey] ?? 0;
        }
        
        return $months;
    }
} 