<?php

namespace App\Modules\Common\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ModuleDatabaseServiceProvider extends ServiceProvider
{
    protected $modules = [
        'Course',
        'Content',
        'Progress',
        'Enrollment',
        'Study',
        'Payment',
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
        $this->loadModuleMigrations();
    }

    protected function loadModuleMigrations(): void
    {
        foreach ($this->modules as $module) {
            $migrationPath = base_path("app/Modules/{$module}/Databases/Migrations");

            if (File::exists($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }
        }
    }
}
