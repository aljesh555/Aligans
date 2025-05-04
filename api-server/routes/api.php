<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\StorefrontController;
use App\Http\Controllers\ShippingFormController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\HeroSectionController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\LogoController;
use App\Http\Controllers\Api\AdminLoginController;
use App\Http\Controllers\API\ProductDetailsController;
use App\Http\Controllers\API\SocialMediaController;
use App\Http\Controllers\API\SettingsController;
use App\Http\Controllers\Api\AboutUsController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\TermsController;
use App\Http\Controllers\Api\PrivacyPolicyController;
use App\Http\Controllers\Api\ShippingPolicyController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\Api\StaticPageController;
use App\Http\Controllers\Api\BrandController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Debug routes for development (REMOVE IN PRODUCTION)
Route::get('/debug/orders', function () {
    try {
        // Load orders with all relevant relationships
        $orders = \App\Models\Order::with([
            'orderItems', 
            'orderItems.product', 
            'user',
            'shippingForm'
        ])
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Add some more detailed information about each order for debugging
        foreach ($orders as $order) {
            // Calculate total items
            $order->total_items = $order->orderItems->sum('quantity');
            
            // Ensure order items have product name if relationship is missing
            foreach ($order->orderItems as $item) {
                if (!$item->product_name && $item->product) {
                    $item->product_name = $item->product->name;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'count' => $orders->count(),
            'data' => $orders
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Public logo route
Route::get('/logo/active', [LogoController::class, 'getActive']);
Route::post('/logos/convert-to-base64', [LogoController::class, 'convertToBase64']);

// New route to fetch logo from settings table
Route::get('/settings/logo', [SettingsController::class, 'getLogo']);

// Banner routes
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/banners/{id}', [BannerController::class, 'show']);

// Storefront public routes
Route::get('/storefront', [StorefrontController::class, 'getHomepage']);
Route::get('/storefront/products', [StorefrontController::class, 'getProducts']);
Route::get('/storefront/categories', [StorefrontController::class, 'getCategories']);
Route::get('/storefront/events', [StorefrontController::class, 'getEvents']);
Route::get('/storefront/teams', [StorefrontController::class, 'getTeams']);
Route::get('/storefront/teams/{id}', [StorefrontController::class, 'getTeam']);
Route::get('/storefront/featured-products', [StorefrontController::class, 'getFeaturedProducts']);
Route::get('/storefront/sale-products', [StorefrontController::class, 'getSaleProducts']);

// Hero Section routes
Route::get('/hero-section', [HeroSectionController::class, 'index']);
Route::get('/hero-section/active', [HeroSectionController::class, 'getActive']);

// Legacy public routes (keeping for backward compatibility)
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::get('/products/by-category/{category}', [ProductController::class, 'getByCategory']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::get('/categories/{category}/subcategories', [CategoryController::class, 'getSubcategories']);
Route::apiResource('events', EventController::class)->only(['index', 'show']);
Route::apiResource('teams', TeamController::class)->only(['index', 'show']);
Route::get('/teams/{team}/players', [PlayerController::class, 'getByTeam']);

// Logo routes
Route::get('/logo/active', [LogoController::class, 'getActive']);

// Admin Login routes
// Route::post('/admin/login', [AdminLoginController::class, 'checkCredentials']);

// Public route to get active social media links
Route::get('/social-media/active', [SocialMediaController::class, 'getActive']);

// Public route to get social media links
Route::get('/settings/social-media', [SettingsController::class, 'getSocialMedia']);
Route::get('/settings/social-links', [SettingsController::class, 'getSocialLinks']);
Route::post('/settings/social-links', [SettingsController::class, 'updateSocialLinks']);

// Public route to get customer care information
Route::get('/settings/customer-care', [SettingsController::class, 'getCustomerCare']);

// Terms & Conditions routes
Route::get('/settings/terms-conditions', [SettingsController::class, 'getTermsConditions']);
Route::get('/terms', [TermsController::class, 'getTerms']);

// Privacy Policy routes
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'getPrivacyPolicies']);

// Shipping Policy routes
Route::get('/shipping-policy', [ShippingPolicyController::class, 'getShippingPolicies']);

// Public route to get footer message
Route::get('/settings/message', [SettingsController::class, 'getMessage']);

// Public route to get header announcement
Route::get('/settings/header-announcement', [SettingsController::class, 'getHeaderAnnouncement']);

// About Us public route
Route::get('/about', [AboutUsController::class, 'index']);

// Contact form submission route
Route::post('/contact', [ContactController::class, 'store']);

// Blog routes
Route::get('/blog/posts', [BlogController::class, 'index']);
Route::get('/blog/posts/latest', [BlogController::class, 'latest']);
Route::get('/blog/posts/{slug}', [BlogController::class, 'show']);

// FAQ routes
Route::get('/faqs', [FaqController::class, 'index']);
Route::get('/faqs/categories', [FaqController::class, 'getCategories']);
Route::get('/faqs/category/{category}', [FaqController::class, 'getByCategory']);

// Product-related routes
Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
// Add new route to fetch product images
Route::get('/products/{id}/images', [\App\Http\Controllers\API\ProductDetailsController::class, 'getProductImages']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User profile routes
    Route::get('/user/profile', [UserController::class, 'getProfile']);
    Route::post('/user/update-profile', [UserController::class, 'updateProfile']);
    
    // User routes (admin only)
    Route::apiResource('users', UserController::class);
    
    // Branch routes (admin/manager only)
    Route::apiResource('branches', BranchController::class);
    
    // Category routes (create, update, delete - protected)
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    
    // Product routes (create, update, delete - protected)
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    
    // Cart routes (for customers)
    Route::apiResource('carts', CartController::class);
    Route::post('/carts/add-item', [CartController::class, 'addItem']);
    Route::post('/carts/remove-item', [CartController::class, 'removeItem']);
    
    // Order routes
    Route::apiResource('orders', OrderController::class);
    Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus']);
    Route::post('/orders/{orderId}/send-confirmation', [OrderController::class, 'sendConfirmation']);
    Route::get('/orders/{orderId}/invoice', [InvoiceController::class, 'download'])->name('api.orders.invoice');
    Route::get('/orders/{orderId}/invoice/view', [InvoiceController::class, 'view'])->name('api.orders.invoice.view');
    
    // Event routes (create, update, delete - protected)
    Route::apiResource('events', EventController::class)->except(['index', 'show']);
    
    // Team routes (create, update, delete - protected)
    Route::apiResource('teams', TeamController::class)->except(['index', 'show']);
    
    // Player routes
    Route::apiResource('players', PlayerController::class);
    
    // Hero Section protected routes (admin only)
    Route::post('/hero-section', [HeroSectionController::class, 'store']);
    Route::put('/hero-section/{id}', [HeroSectionController::class, 'update']);
    Route::delete('/hero-section/{id}', [HeroSectionController::class, 'destroy']);
    
    // Attendance routes (for staff management)
    Route::apiResource('attendances', AttendanceController::class);
    Route::get('/attendances/by-user/{user}', [AttendanceController::class, 'getByUser']);
    
    // Salary routes (for staff management)
    Route::apiResource('salaries', SalaryController::class);
    Route::get('/salaries/by-user/{user}', [SalaryController::class, 'getByUser']);
    
    // Shipping Form routes (for customers)
    Route::apiResource('shipping-forms', ShippingFormController::class);
    Route::get('/shipping-forms/default', [ShippingFormController::class, 'getDefault']);

    // Checkout and Payment Routes
    Route::post('/checkout', [CheckoutController::class, 'processCheckout']);
    Route::post('/payments/esewa/verify', [CheckoutController::class, 'verifyEsewaPayment']);
    Route::get('/orders', [CheckoutController::class, 'getUserOrders']);
    Route::get('/orders/{orderId}', [CheckoutController::class, 'getOrderDetails']);
    Route::get('/user/orders', [CheckoutController::class, 'getUserOrders']);

    // Admin routes
    // Route::prefix('admin')->group(function () {
    //     // Admin Product Management
    //     // Route::apiResource('products', AdminProductController::class);
        
    //     // Admin Category Management
    //     Route::apiResource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    //     Route::get('parent-categories', [\App\Http\Controllers\Admin\CategoryController::class, 'getParentCategories']);
    //     Route::get('categories/{category}/subcategories', [\App\Http\Controllers\Admin\CategoryController::class, 'getSubcategories']);

    //     // Admin Blog routes (if needed for direct API access)
    //     // Route::apiResource('blog-posts', \App\Http\Controllers\Admin\BlogPostController::class);

    //     // Admin wishlist routes
    //     Route::get('/wishlist', [WishlistController::class, 'adminIndex']);
    //     Route::get('/wishlist/stats', [WishlistController::class, 'adminStats']);
    // });

    // Protected banner routes (admin only)
    Route::post('/banners', [BannerController::class, 'store']);
    Route::put('/banners/{id}', [BannerController::class, 'update']);
    Route::delete('/banners/{id}', [BannerController::class, 'destroy']);

    // Logo management
    Route::get('/logos', [LogoController::class, 'index']);
    Route::post('/logos', [LogoController::class, 'store']);
    Route::get('/logos/{id}', [LogoController::class, 'show']);
    Route::put('/logos/{id}', [LogoController::class, 'update']);
    Route::delete('/logos/{id}', [LogoController::class, 'destroy']);
    Route::put('/logos/{id}/activate', [LogoController::class, 'setActive']);
    Route::post('/logos/convert-to-base64', [LogoController::class, 'convertToBase64']);

    // Admin Login management
    // Route::get('/admin/admin-logins', [AdminLoginController::class, 'index']);
    // Route::post('/admin/admin-logins', [AdminLoginController::class, 'store']);
    // Route::get('/admin/admin-logins/{id}', [AdminLoginController::class, 'show']);
    // Route::put('/admin/admin-logins/{id}', [AdminLoginController::class, 'update']);
    // Route::delete('/admin/admin-logins/{id}', [AdminLoginController::class, 'destroy']);

    // Protected routes for social media (admin only)
    Route::apiResource('social-media', SocialMediaController::class);

    // Protected route to set social media settings (admin only)
    Route::post('/settings/social-media', [SettingsController::class, 'storeSocialMediaSettings']);

    // Customer Care Settings routes (admin only)
    Route::post('/settings/customer-care', [SettingsController::class, 'updateCustomerCare']);

    // Header Announcement Settings route (admin only)
    Route::post('/settings/header-announcement', [SettingsController::class, 'updateHeaderAnnouncement']);

    // Terms & Conditions admin routes
    Route::post('/settings/terms-conditions', [SettingsController::class, 'updateTermsConditions']);
    Route::post('/terms', [TermsController::class, 'updateTerms']);

    // Reviews routes (for authenticated users)
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
    Route::get('/user/reviews', [ReviewController::class, 'userReviews']);

    // Wishlist routes for authenticated users
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check']);
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy']);
});

// Product Details, Specifications and Reviews routes
Route::get('products/{id}/details', [ProductDetailsController::class, 'getProductDetails']);
Route::get('products/{id}/specifications', [ProductDetailsController::class, 'getProductSpecifications']);
Route::get('products/{id}/reviews', [ProductDetailsController::class, 'getProductReviews']);
Route::post('products/{id}/reviews', [ProductDetailsController::class, 'submitReview']);
Route::get('products/{id}/related', [ProductDetailsController::class, 'getRelatedProducts']);

// Add the following routes along with the other public settings routes

// Footer about text route
Route::get('/settings/footer-about-text', [SettingsController::class, 'getFooterAboutText']);

// Get setting by key route
Route::get('/settings/by-key/{key}', [SettingsController::class, 'getByKey']);

// Add a direct route for footer about text
Route::get('/footer-about-text', function() {
    return response()->json([
        'success' => true,
        'data' => 'This is Aljesh Raut'
    ]);
});

// Static Pages routes
Route::get('/static-pages', [StaticPageController::class, 'index']);
Route::get('/static-pages/{slug}', [StaticPageController::class, 'show']);

// Brand routes
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/featured', [BrandController::class, 'featured']);
Route::get('/brands/{slug}', [BrandController::class, 'show']);
