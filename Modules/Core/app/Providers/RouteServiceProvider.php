<?php

namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'Core';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapApiV1Routes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')->prefix('api')->name('api.')->group(module_path($this->name, '/routes/api.php'));
    }

    protected function mapApiV1Routes(): void
    {
        Route::middleware('api')->prefix('api/v1')->name('api.v1.')->group(module_path($this->name, '/routes/api_v1.php'));
        Route::middleware('api')->prefix('api/v1/user')->name('api.v1.user.')->group(module_path($this->name, '/routes/api_user_v1.php'));
        Route::middleware('api')->prefix('api/v1/admin')->name('api.v1.admin.')->group(module_path($this->name, '/routes/api_admin_v1.php'));
    }
}
