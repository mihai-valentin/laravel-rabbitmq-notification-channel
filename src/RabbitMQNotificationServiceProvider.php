<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use LaravelRabbitmqNotificationChannel\Broker\Publisher;
use LaravelRabbitmqNotificationChannel\Broker\RabbitMQConnection;
use LaravelRabbitmqNotificationChannel\Broker\RabbitMQPublisher;
use LaravelRabbitmqNotificationChannel\Channel\Channel;
use LaravelRabbitmqNotificationChannel\Channel\RabbitMQChannel;
use LaravelRabbitmqNotificationChannel\Console\PublishCommand;
use LaravelRabbitmqNotificationChannel\Console\RabbitMQNotificationMakeCommand;
use LaravelRabbitmqNotificationChannel\Mapper\MessageMapper;
use LaravelRabbitmqNotificationChannel\Mapper\RabbitMQMessageMapper;

final class RabbitMQNotificationServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->registerCommands();
        $this->registerConfig();
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

    public function registerCommands(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            PublishCommand::class,
            RabbitMQNotificationMakeCommand::class,
        ]);
    }

    private function registerConfig(): void
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
        $this->app->bind(MessageMapper::class, RabbitMQMessageMapper::class);

        $this->app->bind(RabbitMQConnection::class, static function () {
            return new RabbitMQConnection(
                Config::get('rabbitmq-notification-channel.rabbitmq.host'),
                Config::get('rabbitmq-notification-channel.rabbitmq.port'),
                Config::get('rabbitmq-notification-channel.rabbitmq.user'),
                Config::get('rabbitmq-notification-channel.rabbitmq.password'),
            );
        });

        $this->app->bind(Publisher::class, static function (Application $app) {
            return new RabbitMQPublisher(
                Config::get('rabbitmq-notification-channel.default_queue'),
                $app->make(RabbitMQConnection::class),
                $app->make(MessageMapper::class)
            );
        });

        $this->app->bind(Channel::class, RabbitMQChannel::class);
    }

    private function extendNotificationChannelManager(): void
    {
        Notification::resolved(static function (ChannelManager $channelManager) {
            $channelManager->extend('rabbitmq', static fn($app) => $app->make(RabbitMQChannel::class));
        });
    }
}
