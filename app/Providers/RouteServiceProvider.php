<?php

namespace digipos\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'digipos\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    public function map()
    {
        $this->mapAdminRoutes();
        $this->mapApiRoutes();
        $this->mapFrontRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapFrontRoutes()
    {
        Route::group([
            'namespace' => 'Front',
            'prefix' => '/',
            'middleware' => 'web',
            'namespace' => $this->namespace.'\Front',
        ], function ($router) {
            require base_path('routes/front.php');
        });
    }

    protected function mapApiRoutes()
    {
        Route::group([
            'namespace' => 'Api',
            'prefix' => 'api',
            'middleware' => 'api',
            'namespace' => $this->namespace.'\Api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::group([
            'namespace' => 'Admin',
            'middleware' => 'web',
            'prefix' => '_admin',
            'namespace' => $this->namespace.'\Admin',
        ], function ($router) {
            require base_path('routes/admin.php');
        });
    }
}
