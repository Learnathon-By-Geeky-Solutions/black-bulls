<?php

namespace App\Modules\Common\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleRouteServiceProvider extends ServiceProvider
{
    protected $modules = [
        'Course',
        'Auth',
        'Content',
        'Progress',
        'Enrollment'
        // Add other modules here
    ];

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
        $this->loadModuleRoutes();

    }

    protected function loadModuleRoutes(): void
    {
        foreach ($this->modules as $module) {
            $routeFile = base_path("app/Modules/{$module}/Routes/api.php");

            if (File::exists($routeFile)) {
                Route::middleware('api')
                    ->prefix('api')
                    ->group($routeFile);
            }
        }
    }
}
