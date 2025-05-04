<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class FilamentAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register custom auth route for Filament
        Route::middleware('web')
            ->get('/apanel/login', function() {
                if (Auth::check()) {
                    return redirect('/apanel');
                }
                return view('auth.login');
            })
            ->name('filament.apanel.auth.login');
    }
} 