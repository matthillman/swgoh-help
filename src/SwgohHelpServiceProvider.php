<?php

namespace SwgohHelp;

use GuzzleHttp\Client;
use GuzzleHttp\RedirectMiddleware;
use Illuminate\Support\ServiceProvider;

class SwgohHelpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('swgoh', function () {
            return new API;
        });

        $this->app->bind('guzzle', function () {
            $config = isset($this->app['config']['guzzle']) ? $this->app['config']['guzzle'] : [];
            return new Client($config);
        });

        $this->app->bind('goutte', function () {
            return new Client;
        });

        config([
            'redirect.history.header' => RedirectMiddleware::HISTORY_HEADER
        ]);
    }
}
