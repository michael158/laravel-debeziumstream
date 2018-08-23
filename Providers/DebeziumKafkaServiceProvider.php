<?php

namespace MichaelDouglas\DebeziumStream\Providers;

use Illuminate\Support\ServiceProvider;
use MichaelDouglas\DebeziumStream\Commands\ConnectDebezium;
use MichaelDouglas\DebeziumStream\Commands\DeleteKafkaConnection;
use MichaelDouglas\DebeziumStream\Commands\DetailKafkaConnection;
use MichaelDouglas\DebeziumStream\Commands\KafkaListen;
use MichaelDouglas\DebeziumStream\Commands\ShowKafkaConnections;

class DebeziumKafkaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // PUBLISH CONFIGS //
        $this->publishes([
            __DIR__ . '/../config.php' => config_path('kafka.php')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // REGISTER COMMANDS //
        $this->commands([
            ConnectDebezium::class,
            DeleteKafkaConnection::class,
            DetailKafkaConnection::class,
            ShowKafkaConnections::class,
            KafkaListen::class
        ]);

        // REGISTER CONFIGS //
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'kafka.php');
    }
}
