<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Main categories
        $categories = [
            ['name' => 'Jerseys', 'image_url' => 'https://images.unsplash.com/photo-1580087256394-dc596e1c8f4f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Equipment', 'image_url' => 'https://images.unsplash.com/photo-1607962378212-92dcb5a30dd8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Accessories', 'image_url' => 'https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Footwear', 'image_url' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Training', 'image_url' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Apparel', 'image_url' => 'https://images.unsplash.com/photo-1525171254639-d04267f8677f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Team Support', 'image_url' => 'https://images.unsplash.com/photo-1508098682722-e99c643e7485?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Fan Gear', 'image_url' => 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
        ];

        $categoryIds = [];
        
        foreach ($categories as $category) {
            $categoryIds[] = Category::create($category)->id;
        }
        
        // Subcategories
        $subcategories = [
            // Jerseys subcategories (categoryIds[0])
            ['name' => 'Home Jerseys', 'parent_id' => $categoryIds[0], 'image_url' => 'https://images.unsplash.com/photo-1577212017308-55f90828eae6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Away Jerseys', 'parent_id' => $categoryIds[0], 'image_url' => 'https://images.unsplash.com/photo-1560073744-7643b964bdf4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Special Edition', 'parent_id' => $categoryIds[0], 'image_url' => 'https://images.unsplash.com/photo-1525769428041-f1970dc7324e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            
            // Equipment subcategories (categoryIds[1])
            ['name' => 'Balls', 'parent_id' => $categoryIds[1], 'image_url' => 'https://images.unsplash.com/photo-1542652694-40abf526446e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Protective Gear', 'parent_id' => $categoryIds[1], 'image_url' => 'https://images.unsplash.com/photo-1593766789051-6d3dea544e60?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Training Equipment', 'parent_id' => $categoryIds[1], 'image_url' => 'https://images.unsplash.com/photo-1605296867304-46d5465a13f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            
            // Accessories subcategories (categoryIds[2])
            ['name' => 'Bags', 'parent_id' => $categoryIds[2], 'image_url' => 'https://images.unsplash.com/photo-1598532163257-ae3c6b2524b6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Hats', 'parent_id' => $categoryIds[2], 'image_url' => 'https://images.unsplash.com/photo-1521369909029-2afed882baee?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Memorabilia', 'parent_id' => $categoryIds[2], 'image_url' => 'https://images.unsplash.com/photo-1617040619263-41c5a9ca7521?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            
            // Footwear subcategories (categoryIds[3])
            ['name' => 'Cleats', 'parent_id' => $categoryIds[3], 'image_url' => 'https://images.unsplash.com/photo-1511152001146-59ee0e558680?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Indoor Shoes', 'parent_id' => $categoryIds[3], 'image_url' => 'https://images.unsplash.com/photo-1575537302964-96cd47c06b1b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Casual Shoes', 'parent_id' => $categoryIds[3], 'image_url' => 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            
            // Training subcategories (categoryIds[4])
            ['name' => 'Training Shirts', 'parent_id' => $categoryIds[4], 'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Training Shorts', 'parent_id' => $categoryIds[4], 'image_url' => 'https://images.unsplash.com/photo-1567600134-4afff4c52e31?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Training Accessories', 'parent_id' => $categoryIds[4], 'image_url' => 'https://images.unsplash.com/photo-1571019613914-85f342c6a11e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            
            // Apparel subcategories (categoryIds[5])
            ['name' => 'T-Shirts', 'parent_id' => $categoryIds[5], 'image_url' => 'https://images.unsplash.com/photo-1523381294911-8d3cead13475?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Hoodies', 'parent_id' => $categoryIds[5], 'image_url' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Jackets', 'parent_id' => $categoryIds[5], 'image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            
            // Team Support subcategories (categoryIds[6])
            ['name' => 'Flags', 'parent_id' => $categoryIds[6], 'image_url' => 'https://images.unsplash.com/photo-1605005511236-01fbf7fb738f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Scarves', 'parent_id' => $categoryIds[6], 'image_url' => 'https://images.unsplash.com/photo-1520903920243-76fba2ad2441?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Banners', 'parent_id' => $categoryIds[6], 'image_url' => 'https://images.unsplash.com/photo-1552039431-71d34d48e244?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            
            // Fan Gear subcategories (categoryIds[7])
            ['name' => 'Collectibles', 'parent_id' => $categoryIds[7], 'image_url' => 'https://images.unsplash.com/photo-1588515724527-074a7a56616c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Autographed Items', 'parent_id' => $categoryIds[7], 'image_url' => 'https://images.unsplash.com/photo-1569937756447-1d44f657981b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Game Day Essentials', 'parent_id' => $categoryIds[7], 'image_url' => 'https://images.unsplash.com/photo-1530620027867-bc072ef62134?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            
            // Additional new subcategories
            ['name' => 'Helmets', 'parent_id' => $categoryIds[1], 'image_url' => 'https://images.unsplash.com/photo-1501619951397-5ba40d0f75da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Cricket Shoes', 'parent_id' => $categoryIds[3], 'image_url' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
            ['name' => 'Pads & Gloves', 'parent_id' => $categoryIds[1], 'image_url' => 'https://images.unsplash.com/photo-1571731956672-f2b94d7dd0cb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80'],
        ];
        
        foreach ($subcategories as $subcategory) {
            Category::create($subcategory);
        }
    }
} 