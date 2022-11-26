<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications;

use Illuminate\Support\Facades\Config;
use LaravelRabbitmqNotifications\Broker\RabbitMQConnection;
use LaravelRabbitmqNotifications\Broker\RabbitMQPublisher;
use LaravelRabbitmqNotifications\Channel\RabbitMQNotificationsChannel;
use LaravelRabbitmqNotifications\Mapper\MessageDataMapper;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class RabbitMQNotificationsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(RabbitMQConnection::class, static function () {
            return new RabbitMQConnection(
                host: Config::get('notifications_microservice.rabbit_mq.host'),
                port: Config::get('notifications_microservice.rabbit_mq.port'),
                user: Config::get('notifications_microservice.rabbit_mq.user'),
                password: Config::get('notifications_microservice.rabbit_mq.password'),
            );
        });

        $this->app->bind(RabbitMQPublisher::class, static function (Application $app) {
            return new RabbitMQPublisher(
                $app->make(RabbitMQConnection::class),
                Config::get('notifications_microservice.queue_name'),
                new MessageDataMapper(),
            );
        });

        $this->app->bind(RabbitMQNotificationsChannel::class, function ($app) {
            $retriesCount = (int) Config::get('notifications_microservice.send_retries_count');

            return new RabbitMQNotificationsChannel($app->make(RabbitMQPublisher::class), $retriesCount);
        });
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [
            RabbitMQNotificationsChannel::class,
        ];
    }
}
