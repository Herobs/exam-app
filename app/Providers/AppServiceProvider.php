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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // register ExamAuthenticate middleware as singleton
        $this->app->singleton('App\Http\Middleware\ExamAuthenticate');
        // register AdminAuthenticate middleware as singleton
        $this->app->singleton('App\Http\Middleware\AdminAuthenticate');
    }
}
