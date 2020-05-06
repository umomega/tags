<?php

namespace Umomega\Tags\Providers;

use Illuminate\Support\ServiceProvider;

class TagsServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register any tags services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/tags')], 'lang');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'tags');

        $this->loadMigrationsFrom(__DIR__ . '/../../migrations');
    }

}
