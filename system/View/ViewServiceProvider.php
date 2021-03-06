<?php

namespace Quasar\View;

use Quasar\View\Factory;
use Quasar\ServiceProvider;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('view', function ($app)
        {
            return new Factory($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('view');
    }
}
