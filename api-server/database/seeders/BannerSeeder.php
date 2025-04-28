<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banners = [
            [
                'title' => 'Summer Collection',
                'description' => 'Discover our new summer fashion collection with up to 40% discount.',
                'button_text' => 'Shop Now',
                'button_link' => '/products/sale',
                'image_url' => 'banners/summer-collection.jpg',
                'is_active' => true,
                'priority' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'New Arrivals',
                'description' => 'Check out our latest products just arrived this week!',
                'button_text' => 'View Collection',
                'button_link' => '/products',
                'image_url' => 'banners/new-arrivals.jpg',
                'is_active' => true,
                'priority' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Special Offer',
                'description' => 'Limited time offer: Get free shipping on all orders over $50',
                'button_text' => 'Learn More',
                'button_link' => '/shipping-policy',
                'image_url' => 'banners/special-offer.jpg',
                'is_active' => true,
                'priority' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('banners')->insert($banners);
    }
}
