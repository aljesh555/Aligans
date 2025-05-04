<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Customer Care Settings
    Route::get('/settings/customer-care', [\App\Http\Controllers\Admin\SettingsController::class, 'showCustomerCareForm'])
        ->name('settings.customer-care');
    Route::post('/settings/customer-care', [\App\Http\Controllers\Admin\SettingsController::class, 'updateCustomerCare'])
        ->name('settings.customer-care.update');
    
    // Terms & Conditions Settings (old approach via settings table)
    Route::get('/settings/terms', [\App\Http\Controllers\Admin\SettingsController::class, 'showTermsForm'])
        ->name('settings.terms');
    Route::post('/settings/terms', [\App\Http\Controllers\Admin\SettingsController::class, 'updateTerms'])
        ->name('settings.terms.update');
    
    // Header Announcement Settings
    Route::get('/settings/header-announcement', [\App\Http\Controllers\Admin\SettingsController::class, 'showHeaderAnnouncementForm'])
        ->name('settings.header-announcement');
    Route::post('/settings/header-announcement', [\App\Http\Controllers\Admin\SettingsController::class, 'updateHeaderAnnouncement'])
        ->name('settings.header-announcement.update');
    
    // New dedicated Terms & Conditions routes
    Route::get('/terms', [\App\Http\Controllers\Admin\TermsController::class, 'index'])
        ->name('terms.index');
    Route::get('/terms/edit', [\App\Http\Controllers\Admin\TermsController::class, 'edit'])
        ->name('terms.edit');
    Route::post('/terms', [\App\Http\Controllers\Admin\TermsController::class, 'update'])
        ->name('terms.update');
    Route::get('/terms/history', [\App\Http\Controllers\Admin\TermsController::class, 'history'])
        ->name('terms.history');
    
    // Privacy Policy routes
    Route::get('/privacy-policy', [\App\Http\Controllers\Admin\PrivacyPolicyController::class, 'index'])
        ->name('privacy-policy.index');
    Route::get('/privacy-policy/edit', [\App\Http\Controllers\Admin\PrivacyPolicyController::class, 'edit'])
        ->name('privacy-policy.edit');
    Route::post('/privacy-policy', [\App\Http\Controllers\Admin\PrivacyPolicyController::class, 'update'])
        ->name('privacy-policy.update');
    Route::get('/privacy-policy/history', [\App\Http\Controllers\Admin\PrivacyPolicyController::class, 'history'])
        ->name('privacy-policy.history');
    
    // Other admin routes...
});

// Define the Filament login route explicitly with the exact name
Route::get('/apanel/login', function() {
    if (Auth::check()) {
        return redirect('/apanel');
    }
    return view('auth.login');
})->name('filament.admin.auth.login');

// Define the login post route
Route::post('/apanel/login', function(Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/apanel');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->name('filament.admin.auth.login.store');
