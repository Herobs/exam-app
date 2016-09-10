<?php

namespace App\Providers;

use Judge\Judge;
use Illuminate\Support\ServiceProvider;

class JudgeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Judge::class, function($app) {
            return new Judge(config('judge.uri'), config('judge.id'), config('judge.secret'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Judge::class];
    }
}
