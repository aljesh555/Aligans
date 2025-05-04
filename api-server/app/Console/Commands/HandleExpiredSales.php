<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HandleExpiredSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:handle-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates products with expired sales to remove their sale status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for expired sales...');
        
        $now = Carbon::now();
        
        // Find all products that are on sale and have an expired sale end date
        $expiredProducts = Product::where('on_sale', true)
            ->whereNotNull('sale_ends_at')
            ->where('sale_ends_at', '<', $now)
            ->get();
            
        $count = $expiredProducts->count();
        
        if ($count === 0) {
            $this->info('No expired sales found.');
            return 0;
        }
        
        $this->info("Found {$count} products with expired sales. Processing...");
        
        foreach ($expiredProducts as $product) {
            $this->info("Updating product #{$product->id}: {$product->name}");
            
            // Log the change
            Log::info("Sale expired for product: {$product->name} (ID: {$product->id})");
            Log::info("Previous sale price: {$product->discount_price}, End date: {$product->sale_ends_at}");
            
            // Update the product
            $product->on_sale = false;
            $product->discount_price = null;
            $product->discount_percent = null;
            $product->sale_ends_at = null;
            $product->save();
            
            $this->info("Updated product: {$product->name} - Sale removed");
        }
        
        $this->info("Completed processing {$count} expired sales.");
        
        return 0;
    }
} 