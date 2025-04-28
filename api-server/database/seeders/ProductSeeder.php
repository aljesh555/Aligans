<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStock;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();

        // Get or create a branch for product stocks
        $branch = Branch::first();
        if (!$branch) {
            $branch = Branch::create([
                'name' => 'Main Branch',
                'location' => 'Downtown',
                'contact_no' => '123-456-7890',
            ]);
        }

        // Get all categories
        $categoryIds = $categories->pluck('id')->toArray();
        
        // Get specific category IDs for better organization
        $jerseyCategory = Category::where('name', 'Jerseys')->first()->id;
        $equipmentCategory = Category::where('name', 'Equipment')->first()->id;
        $accessoriesCategory = Category::where('name', 'Accessories')->first()->id;
        $footwearCategory = Category::where('name', 'Footwear')->first()->id;
        $trainingCategory = Category::where('name', 'Training')->first()->id;
        
        // Get subcategory IDs
        $homeJerseysId = Category::where('name', 'Home Jerseys')->first()->id;
        $awayJerseysId = Category::where('name', 'Away Jerseys')->first()->id;
        $specialEditionId = Category::where('name', 'Special Edition')->first()->id;
        
        $ballsId = Category::where('name', 'Balls')->first()->id;
        $protectiveGearId = Category::where('name', 'Protective Gear')->first()->id;
        $trainingEquipmentId = Category::where('name', 'Training Equipment')->first()->id;
        
        $bagsId = Category::where('name', 'Bags')->first()->id;
        $hatsId = Category::where('name', 'Hats')->first()->id;
        $memorabiliaId = Category::where('name', 'Memorabilia')->first()->id;
        
        $cleatsId = Category::where('name', 'Cleats')->first()->id;
        $indoorShoesId = Category::where('name', 'Indoor Shoes')->first()->id;
        $casualShoesId = Category::where('name', 'Casual Shoes')->first()->id;
        
        $trainingShirtsId = Category::where('name', 'Training Shirts')->first()->id;
        $trainingShortsId = Category::where('name', 'Training Shorts')->first()->id;
        $trainingAccessoriesId = Category::where('name', 'Training Accessories')->first()->id;
        
        // New subcategory IDs
        $helmetsId = Category::where('name', 'Helmets')->first()->id;
        $cricketShoesId = Category::where('name', 'Cricket Shoes')->first()->id;
        $padsGlovesId = Category::where('name', 'Pads & Gloves')->first()->id;
        
        // Products for Jerseys
        $jerseyProducts = [
            [
                'name' => 'Official Home Jersey 2025',
                'description' => 'The official home jersey for the 2025 season. Made with breathable fabric and featuring the latest team design.',
                'price' => 89.99,
                'category_id' => $homeJerseysId,
                'image_url' => 'https://picsum.photos/id/999/800/800',
                'featured' => true
            ],
            [
                'name' => 'Away Jersey - Limited Edition',
                'description' => 'Special away jersey with unique design elements. Perfect for true fans who want to stand out.',
                'price' => 94.99,
                'category_id' => $awayJerseysId,
                'image_url' => 'https://picsum.photos/id/1071/800/800',
                'featured' => false
            ],
            [
                'name' => 'Vintage Heritage Jersey',
                'description' => 'A throwback to the classic design from the 1980s championship era. Collector\'s item.',
                'price' => 119.99,
                'category_id' => $specialEditionId,
                'image_url' => 'https://picsum.photos/id/1072/800/800',
                'featured' => true
            ],
            [
                'name' => 'Youth Home Jersey',
                'description' => 'Sized for younger fans, with the same quality as the adult versions.',
                'price' => 64.99,
                'category_id' => $homeJerseysId,
                'image_url' => 'https://picsum.photos/id/1073/800/800',
                'featured' => false
            ],
            [
                'name' => 'Premium Third Kit',
                'description' => 'The exclusive third kit with premium fabric and limited availability.',
                'price' => 109.99,
                'category_id' => $specialEditionId,
                'image_url' => 'https://picsum.photos/id/1074/800/800',
                'featured' => true
            ],
            [
                'name' => 'Goalkeeper Jersey 2025',
                'description' => 'Professional goalkeeper jersey with padded elbows and special grip technology.',
                'price' => 99.99,
                'category_id' => $homeJerseysId,
                'image_url' => 'https://picsum.photos/id/1075/800/800',
                'featured' => false
            ],
            [
                'name' => 'Special Edition Anniversary Jersey',
                'description' => 'Commemorating 50 years of excellence with this limited edition jersey.',
                'price' => 149.99,
                'category_id' => $specialEditionId,
                'image_url' => 'https://picsum.photos/id/1076/800/800',
                'featured' => true
            ],
            [
                'name' => 'Away Replica Jersey',
                'description' => 'Affordable replica of the away jersey with great quality and authentic design.',
                'price' => 74.99,
                'category_id' => $awayJerseysId,
                'image_url' => 'https://picsum.photos/id/1077/800/800',
                'featured' => false
            ],
        ];
        
        // Products for Equipment
        $equipmentProducts = [
            [
                'name' => 'Professional Soccer Ball',
                'description' => 'Match-quality soccer ball with excellent durability and performance. FIFA approved.',
                'price' => 129.99,
                'category_id' => $ballsId,
                'image_url' => 'https://picsum.photos/id/1078/800/800',
                'featured' => true
            ],
            [
                'name' => 'Training Soccer Ball Set',
                'description' => 'Set of 6 training balls perfect for team practice and drills.',
                'price' => 199.99,
                'category_id' => $ballsId,
                'image_url' => 'https://picsum.photos/id/1079/800/800',
                'featured' => false
            ],
            [
                'name' => 'Premium Shin Guards',
                'description' => 'Lightweight yet strong shin guards with custom fit technology for maximum protection.',
                'price' => 39.99,
                'category_id' => $protectiveGearId,
                'image_url' => 'https://picsum.photos/id/1080/800/800',
                'featured' => false
            ],
            [
                'name' => 'Goalkeeper Gloves - Pro Level',
                'description' => 'Professional goalkeeper gloves with advanced grip and finger protection.',
                'price' => 89.99,
                'category_id' => $protectiveGearId,
                'image_url' => 'https://picsum.photos/id/1081/800/800',
                'featured' => true
            ],
            [
                'name' => 'Training Cones Set',
                'description' => 'Set of 50 multicolor training cones for drills and practice sessions.',
                'price' => 29.99,
                'category_id' => $trainingEquipmentId,
                'image_url' => 'https://picsum.photos/id/1082/800/800',
                'featured' => false
            ],
            [
                'name' => 'Agility Ladder',
                'description' => '6-meter agility ladder for footwork and speed training with carrying bag.',
                'price' => 44.99,
                'category_id' => $trainingEquipmentId,
                'image_url' => 'https://picsum.photos/id/1083/800/800',
                'featured' => false
            ],
            [
                'name' => 'Indoor Training Ball',
                'description' => 'Special ball designed for indoor training with reduced bounce and better control.',
                'price' => 59.99,
                'category_id' => $ballsId,
                'image_url' => 'https://picsum.photos/id/1084/800/800',
                'featured' => true
            ],
            [
                'name' => 'Youth Soccer Goal',
                'description' => 'Portable youth soccer goal for backyard practice. Easy to assemble and store.',
                'price' => 119.99,
                'category_id' => $trainingEquipmentId,
                'image_url' => 'https://picsum.photos/id/1085/800/800',
                'featured' => false
            ],
        ];
        
        // Products for Accessories
        $accessoriesProducts = [
            [
                'name' => 'Team Backpack',
                'description' => 'Official team backpack with multiple compartments and water-resistant material.',
                'price' => 69.99,
                'category_id' => $bagsId,
                'image_url' => 'https://picsum.photos/id/1086/800/800',
                'featured' => false
            ],
            [
                'name' => 'Sports Duffel Bag',
                'description' => 'Large capacity duffel bag for all your gear with separate shoe compartment.',
                'price' => 89.99,
                'category_id' => $bagsId,
                'image_url' => 'https://picsum.photos/id/1087/800/800',
                'featured' => true
            ],
            [
                'name' => 'Fan Cap',
                'description' => 'Adjustable cap with embroidered team logo. One size fits most.',
                'price' => 29.99,
                'category_id' => $hatsId,
                'image_url' => 'https://picsum.photos/id/1088/800/800',
                'featured' => false
            ],
            [
                'name' => 'Winter Beanie',
                'description' => 'Warm winter beanie with team colors and logo. Perfect for cold game days.',
                'price' => 24.99,
                'category_id' => $hatsId,
                'image_url' => 'https://picsum.photos/id/1089/800/800',
                'featured' => false
            ],
            [
                'name' => 'Signed Team Photo',
                'description' => 'Limited edition team photo signed by all first team players. Includes certificate of authenticity.',
                'price' => 199.99,
                'category_id' => $memorabiliaId,
                'image_url' => 'https://picsum.photos/id/1090/800/800',
                'featured' => true
            ],
            [
                'name' => 'Championship Scarf',
                'description' => 'Commemorative scarf celebrating the recent championship win.',
                'price' => 34.99,
                'category_id' => $memorabiliaId,
                'image_url' => 'https://picsum.photos/id/1091/800/800',
                'featured' => false
            ],
            [
                'name' => 'Gym Sack',
                'description' => 'Lightweight gym sack for small items and quick trips to training.',
                'price' => 19.99,
                'category_id' => $bagsId,
                'image_url' => 'https://picsum.photos/id/1092/800/800',
                'featured' => false
            ],
            [
                'name' => 'Collector\'s Edition Figurine',
                'description' => 'Limited edition figurine of team captain. Highly detailed collectible item.',
                'price' => 129.99,
                'category_id' => $memorabiliaId,
                'image_url' => 'https://picsum.photos/id/1093/800/800',
                'featured' => true
            ],
        ];
        
        // Products for Footwear
        $footwearProducts = [
            [
                'name' => 'Professional Soccer Cleats',
                'description' => 'Top-tier cleats designed for elite performance on natural grass.',
                'price' => 199.99,
                'category_id' => $cleatsId,
                'image_url' => 'https://picsum.photos/id/1094/800/800',
                'featured' => true
            ],
            [
                'name' => 'Youth Soccer Cleats',
                'description' => 'Durable and comfortable cleats designed for young players.',
                'price' => 69.99,
                'category_id' => $cleatsId,
                'image_url' => 'https://picsum.photos/id/1095/800/800',
                'featured' => false
            ],
            [
                'name' => 'Indoor Court Shoes',
                'description' => 'Specialized shoes for indoor soccer with non-marking soles and excellent grip.',
                'price' => 89.99,
                'category_id' => $indoorShoesId,
                'image_url' => 'https://picsum.photos/id/1096/800/800',
                'featured' => false
            ],
            [
                'name' => 'Futsal Shoes',
                'description' => 'Low-profile shoes designed specifically for futsal with enhanced ball control features.',
                'price' => 79.99,
                'category_id' => $indoorShoesId,
                'image_url' => 'https://picsum.photos/id/1097/800/800',
                'featured' => true
            ],
            [
                'name' => 'Team Sneakers',
                'description' => 'Casual sneakers featuring team colors and logo. Perfect for everyday wear.',
                'price' => 79.99,
                'category_id' => $casualShoesId,
                'image_url' => 'https://picsum.photos/id/1098/800/800',
                'featured' => false
            ],
            [
                'name' => 'Recovery Slides',
                'description' => 'Comfortable slides for post-game recovery with cushioned footbed.',
                'price' => 34.99,
                'category_id' => $casualShoesId,
                'image_url' => 'https://picsum.photos/id/1099/800/800',
                'featured' => false
            ],
            [
                'name' => 'All-Terrain Cleats',
                'description' => 'Versatile cleats designed for multiple playing surfaces and conditions.',
                'price' => 129.99,
                'category_id' => $cleatsId,
                'image_url' => 'https://picsum.photos/id/100/800/800',
                'featured' => true
            ],
            [
                'name' => 'Limited Edition Lifestyle Shoes',
                'description' => 'Exclusive design collaboration with famous artist featuring team inspiration.',
                'price' => 149.99,
                'category_id' => $casualShoesId,
                'image_url' => 'https://picsum.photos/id/101/800/800',
                'featured' => true
            ],
        ];
        
        // Products for Training
        $trainingProducts = [
            [
                'name' => 'Performance Training Top',
                'description' => 'Lightweight training top with moisture-wicking technology for intense sessions.',
                'price' => 49.99,
                'category_id' => $trainingShirtsId,
                'image_url' => 'https://picsum.photos/id/102/800/800',
                'featured' => false
            ],
            [
                'name' => 'Training Jersey Set',
                'description' => 'Set of 15 training jerseys in two colors for team practice sessions.',
                'price' => 299.99,
                'category_id' => $trainingShirtsId,
                'image_url' => 'https://picsum.photos/id/103/800/800',
                'featured' => true
            ],
            [
                'name' => 'All-Weather Training Pants',
                'description' => 'Versatile training pants suitable for all weather conditions with zip ankles.',
                'price' => 59.99,
                'category_id' => $trainingShortsId,
                'image_url' => 'https://picsum.photos/id/104/800/800',
                'featured' => false
            ],
            [
                'name' => 'Compression Shorts',
                'description' => 'Performance compression shorts for training and injury prevention.',
                'price' => 34.99,
                'category_id' => $trainingShortsId,
                'image_url' => 'https://picsum.photos/id/105/800/800',
                'featured' => false
            ],
            [
                'name' => 'Training Bib Set',
                'description' => 'Set of 10 lightweight training bibs in multiple colors.',
                'price' => 49.99,
                'category_id' => $trainingAccessoriesId,
                'image_url' => 'https://picsum.photos/id/106/800/800',
                'featured' => false
            ],
            [
                'name' => 'Resistance Training Set',
                'description' => 'Complete set of resistance bands, parachute, and weights for strength training.',
                'price' => 129.99,
                'category_id' => $trainingAccessoriesId,
                'image_url' => 'https://picsum.photos/id/107/800/800',
                'featured' => true
            ],
            [
                'name' => 'Winter Training Jacket',
                'description' => 'Insulated training jacket for cold weather with windproof and waterproof features.',
                'price' => 89.99,
                'category_id' => $trainingShirtsId,
                'image_url' => 'https://picsum.photos/id/108/800/800',
                'featured' => false
            ],
            [
                'name' => 'Performance Tracking System',
                'description' => 'Wearable trackers for analyzing player movement, speed, and performance metrics.',
                'price' => 249.99,
                'category_id' => $trainingAccessoriesId,
                'image_url' => 'https://picsum.photos/id/109/800/800',
                'featured' => true
            ],
        ];
        
        // Combine all products
        $allProducts = array_merge(
            $jerseyProducts, 
            $equipmentProducts, 
            $accessoriesProducts, 
            $footwearProducts, 
            $trainingProducts
        );
        
        // Create the products and their stock
        foreach ($allProducts as $productData) {
            $product = Product::create($productData);
            
            // Create stock entries (between 5-30 items per product)
            $stockQuantity = rand(5, 30);
            ProductStock::create([
                'product_id' => $product->id,
                'branch_id' => $branch->id,
                'quantity' => $stockQuantity
            ]);
        }

        // Use factory to create additional products (commented out until we have a product factory)
        /*
        foreach ($categories as $category) {
            Product::factory()->count(3)->create([
                'category_id' => $category->id
            ]);
        }
        */

        foreach ($accessoriesProducts as $product) {
            $createdProduct = Product::create($product);
            
            // Create some stock for the product
            ProductStock::create([
                'product_id' => $createdProduct->id,
                'branch_id' => $branch->id,
                'quantity' => rand(10, 100)
            ]);
        }
        
        // New Products for Helmets
        $helmetsProducts = [
            [
                'name' => 'Pro Cricket Helmet',
                'description' => 'Professional-grade cricket helmet with advanced protection features, adjustable fit, and ventilation system.',
                'price' => 129.99,
                'category_id' => $helmetsId,
                'image_url' => 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => true
            ],
            [
                'name' => 'Youth Batting Helmet',
                'description' => 'Lightweight helmet designed specifically for young players with enhanced protection and comfortable padding.',
                'price' => 79.99,
                'category_id' => $helmetsId,
                'image_url' => 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => false
            ],
            [
                'name' => 'Elite Cricketer Helmet',
                'description' => 'Top-of-the-line helmet used by professional players, featuring titanium grille and impact absorption technology.',
                'price' => 189.99,
                'category_id' => $helmetsId,
                'image_url' => 'https://images.unsplash.com/photo-1565372595781-912a0f0d2e6e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => true
            ],
        ];
        
        // Products for Cricket Shoes
        $cricketShoesProducts = [
            [
                'name' => 'Premium Cricket Spikes',
                'description' => 'High-performance cricket shoes with metal spikes for maximum grip on natural turf pitches.',
                'price' => 149.99,
                'category_id' => $cricketShoesId,
                'image_url' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => true
            ],
            [
                'name' => 'All-Surface Cricket Shoes',
                'description' => 'Versatile cricket shoes with rubber studs for use on multiple surfaces and indoor nets.',
                'price' => 119.99,
                'category_id' => $cricketShoesId,
                'image_url' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => false
            ],
            [
                'name' => 'Lightweight Cricket Trainers',
                'description' => 'Breathable, lightweight shoes for training sessions and casual matches with extra ankle support.',
                'price' => 99.99,
                'category_id' => $cricketShoesId,
                'image_url' => 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => true
            ],
        ];
        
        // Products for Pads & Gloves
        $padsGlovesProducts = [
            [
                'name' => 'Professional Batting Pads',
                'description' => 'Tournament-level batting pads with high-density foam padding and reinforced knee protection.',
                'price' => 159.99,
                'category_id' => $padsGlovesId,
                'image_url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => true
            ],
            [
                'name' => 'Premium Batting Gloves',
                'description' => 'Superior quality leather batting gloves with extra padding for knuckle protection and enhanced grip.',
                'price' => 89.99,
                'category_id' => $padsGlovesId,
                'image_url' => 'https://images.unsplash.com/photo-1577476005967-43d7bc17fe2f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => false
            ],
            [
                'name' => 'Wicket Keeping Gloves',
                'description' => 'Professional wicket keeping gloves with pre-curved design, reinforced palms and adjustable wrist straps.',
                'price' => 119.99,
                'category_id' => $padsGlovesId,
                'image_url' => 'https://images.unsplash.com/photo-1574358183241-514a4204dce0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => true
            ],
            [
                'name' => 'Youth Cricket Pad Set',
                'description' => 'Complete set of lightweight pads designed for young players, including batting pads and arm guards.',
                'price' => 129.99,
                'category_id' => $padsGlovesId,
                'image_url' => 'https://images.unsplash.com/photo-1590152638573-3c0809209258?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'featured' => false
            ],
        ];
        
        // Create products for Helmets
        foreach ($helmetsProducts as $product) {
            $createdProduct = Product::create($product);
            
            // Create some stock for the product
            ProductStock::create([
                'product_id' => $createdProduct->id,
                'branch_id' => $branch->id,
                'quantity' => rand(5, 30)
            ]);
        }
        
        // Create products for Cricket Shoes
        foreach ($cricketShoesProducts as $product) {
            $createdProduct = Product::create($product);
            
            // Create some stock for the product
            ProductStock::create([
                'product_id' => $createdProduct->id,
                'branch_id' => $branch->id,
                'quantity' => rand(10, 50)
            ]);
        }
        
        // Create products for Pads & Gloves
        foreach ($padsGlovesProducts as $product) {
            $createdProduct = Product::create($product);
            
            // Create some stock for the product
            ProductStock::create([
                'product_id' => $createdProduct->id,
                'branch_id' => $branch->id,
                'quantity' => rand(15, 60)
            ]);
        }

        // Add some sale products
        if (count($categories) > 0) {
            foreach ($categories as $index => $category) {
                // Create 1-2 sale products per category for a total of 6-12 sale products
                $numSaleProducts = rand(1, 2);
                
                for ($i = 0; $i < $numSaleProducts; $i++) {
                    $name = "Sale " . $category->name . " Item " . ($i + 1);
                    $price = rand(3000, 20000) / 100; // Between $30 and $200
                    $discountPercent = rand(10, 50); // Between 10% and 50% discount
                    $discountPrice = round($price * (1 - $discountPercent / 100), 2);
                    
                    Product::create([
                        'name' => $name,
                        'description' => "This is a discounted " . strtolower($category->name) . " product with amazing features!",
                        'price' => $price,
                        'category_id' => $category->id,
                        'image_url' => null,
                        'on_sale' => true,
                        'discount_price' => $discountPrice,
                        'discount_percent' => $discountPercent,
                        'is_new_arrival' => rand(0, 1) ? true : false,
                        'sale_ends_at' => Carbon::now()->addDays(rand(7, 30))->toDateString()
                    ]);
                }
            }
        }
    }
} 