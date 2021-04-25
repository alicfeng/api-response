<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\ServiceProvider;

use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../config/response.php', 'response');
    }

    public function boot()
    {
        $this->publishConfig();
    }

    public function publishConfig()
    {
        $this->publishes(
            [
                __DIR__ . '/../../../config/response.php' => config_path('samego-response.php'),
            ],
            'samego-response'
        );
    }
}
