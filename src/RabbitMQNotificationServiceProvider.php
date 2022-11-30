<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use LaravelRabbitmqNotificationChannel\Broker\RabbitMQConnection;
use LaravelRabbitmqNotificationChannel\Broker\RabbitMQPublisher;
use LaravelRabbitmqNotificationChannel\Channel\RabbitMQChannel;
use LaravelRabbitmqNotificationChannel\Mapper\RabbitMQMessageMapper;

final class RabbitMQNotificationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->publishConfig();

        $this->registerChannels();
        $this->extendNotificationChannelManager();
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [
            RabbitMQChannel::class,
        ];
    }

    private function publishConfig(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $configPath = __DIR__ . '/../config/rabbitmq-notification-channel.php';
        $publishPath = $this->app->configPath('rabbitmq-notification-channel.php');

        $this->publishes([$configPath => $publishPath], 'rabbitmq-notification-channel');
    }

    private function registerChannels(): void
    {
        $this->app->bind(RabbitMQConnection::class, static function () {
            return new RabbitMQConnection(
                Config::get('rabbitmq-notification-channel.rabbitmq.host'),
                Config::get('rabbitmq-notification-channel.rabbitmq.port'),
                Config::get('rabbitmq-notification-channel.rabbitmq.user'),
                Config::get('rabbitmq-notification-channel.rabbitmq.password'),
            );
        });

        $this->app->bind(RabbitMQPublisher::class, static function (Application $app) {
            $defaultQueue = Config::get('rabbitmq-notification-channel.default_queue');

            return new RabbitMQPublisher(
                $defaultQueue,
                $app->make(RabbitMQConnection::class),
                $app->make(RabbitMQMessageMapper::class),
            );
        });

        $this->app->bind(RabbitMQChannel::class, function ($app) {
            return new RabbitMQChannel($app->make(RabbitMQPublisher::class));
        });
    }

    private function extendNotificationChannelManager(): void
    {
        Notification::resolved(static function (ChannelManager $channelManager) {
            $channelManager->extend('rabbitmq', static fn($app) => $app->make(RabbitMQChannel::class));
        });
    }
}
