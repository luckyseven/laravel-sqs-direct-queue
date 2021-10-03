<?php

namespace LuckySeven\SqsDirectQueue\Providers;

use Illuminate\Support\ServiceProvider;
use LuckySeven\SqsDirectQueue\Connectors\SqsDirectQueueConnector;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $manager = $this->app['queue'];

        $manager->addConnector('sqs_direct', function()
        {
            return new SqsDirectQueueConnector();
        });
    }
}
