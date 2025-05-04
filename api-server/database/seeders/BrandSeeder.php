<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test brands
        $brands = [
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'logo_url' => 'brands/adidas.png',
                'description' => 'High-quality sports equipment and apparel.',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'logo_url' => 'brands/nike.png',
                'description' => 'Leading sports brand for athletes of all levels.',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Puma',
                'slug' => 'puma',
                'logo_url' => 'brands/puma.png',
                'description' => 'Performance sports gear for modern athletes.',
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Reebok',
                'slug' => 'reebok',
                'logo_url' => 'brands/reebok.png',
                'description' => 'Classic sports equipment with modern performance features.',
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Under Armour',
                'slug' => 'under-armour',
                'logo_url' => 'brands/under-armour.png',
                'description' => 'Innovative sportswear for optimal performance.',
                'is_featured' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
} 