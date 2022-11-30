<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Channel;

use Illuminate\Support\Facades\Event;
use LaravelRabbitmqNotificationChannel\Broker\RabbitMQPublisher;
use LaravelRabbitmqNotificationChannel\Event\RabbitMQNotificationFailed;
use LaravelRabbitmqNotificationChannel\Exception\BrokerConnectionException;
use LaravelRabbitmqNotificationChannel\RabbitMQNotification;

final class RabbitMQChannel implements Channel
{
    public function __construct(private readonly RabbitMQPublisher $publisher)
    {
    }

    public function send($notifiable, RabbitMQNotification $notification): void
    {
        $message = $notification->toRabbitMQ($notifiable);

        try {
            $this->publisher->publishMessage($message);
        } catch (BrokerConnectionException $exception) {
            Event::dispatch(new RabbitMQNotificationFailed($exception));
        }
    }
}
