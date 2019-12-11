<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      // Fix for "Specified key was too long error" error
      // https://laravel-news.com/laravel-5-4-key-too-long-error
      \Schema::defaultStringLength(191);

      // Set global default for sub navigation to false
      view()->share('subnav', false);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
