<?php

/*
 * What samego team is that is 'one thing, a team, work together'
 */

namespace Samego\Response\ServiceProvider;

use Illuminate\Support\ServiceProvider as FrameworkServiceProvider;
use Samego\Response\Contracts\ApiResponseInterface;
use Samego\Response\Response;

class ServiceProvider extends FrameworkServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../config/api-response.php', 'response');
    }

    public function boot()
    {
        $this->publishConfig();

        $this->app->bind(ApiResponseInterface::class, function () {
            return new Response(config('api-response'));
        });
    }

    public function publishConfig()
    {
        $this->publishes(
            [
                __DIR__ . '/../../../config/api-response.php' => config_path('api-response.php'),
            ],
            'api-response'
        );
    }
}
