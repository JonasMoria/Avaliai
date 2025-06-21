<?php

namespace App\Providers;

use App\Services\Cache\SyncQueueService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->singleton('SyncQueue', function ($app) {
            return new SyncQueueService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}