<?php

namespace App\Modules\Common\Providers;

use App\Modules\Common\Contracts\RepositoryInterface;
use App\Modules\Common\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, function ($app, array $params){

            $model = $params['model'] ?? null;

            if(!$model || !$model instanceof Model){
                throw new \InvalidArgumentException("Model must be an instance of Illuminate\Database\Eloquent\Model");
            }
            
            return new Repository($model);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
