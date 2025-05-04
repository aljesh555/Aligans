<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopSellingProductsWidget extends ChartWidget
{
    protected static ?string $heading = 'Top Selling Products';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';
    
    protected function getData(): array
    {
        // Get top 5 selling products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
            
        // Fetch the product names
        $productIds = $topProducts->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        $labels = [];
        $data = [];
        
        foreach ($topProducts as $item) {
            $productName = $products[$item->product_id]->name ?? "Product #{$item->product_id}";
            $labels[] = strlen($productName) > 20 ? substr($productName, 0, 20) . '...' : $productName;
            $data[] = $item->total_quantity;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Units Sold',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    protected function getType(): string
    {
        return 'doughnut';
    }
} 