<?php

namespace Squashjedi\Basecamp;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Laravel\Socialite\SocialiteServiceProvider;

class BasecampServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(SocialiteServiceProvider::class);
        AliasLoader::getInstance(['Socialite'=> '\Laravel\Socialite\Facades\Socialite']);

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'squashjedi/basecamp');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/squashjedi/basecamp'),
            __DIR__.'/resources/assets/js/components' => base_path('resources/assets/js/components/vendor/squashjedi/basecamp'),
            __DIR__.'/resources/assets/sass' => base_path('resources/assets/sass/vendor/squashjedi/basecamp')
        ], 'basecamp');

        $this->publishes([
            __DIR__.'/resources/assets/js/components' => base_path('resources/assets/js/components/vendor/squashjedi/basecamp')
        ], 'basecamp-components');

        $this->publishes([
            __DIR__.'/resources/assets/sass' => base_path('resources/assets/sass/vendor/squashjedi/basecamp')
        ], 'basecamp-sass');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerEloquentFactoriesFrom(__DIR__.'/database/factories');
        $this->app->bind('Squashjedi\Basecamp\App\Http\Repositories\User\UserRepositoryInterface', 'Squashjedi\Basecamp\App\Http\Repositories\User\DbUserRepository');
    }

    /**
     * Register factories.
     *
     * @param  string  $path
     * @return void
     */
    protected function registerEloquentFactoriesFrom($path)
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }
}