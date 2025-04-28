<?php

use Illuminate\Support\Facades\Route;

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
