<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Channel;

use Illuminate\Support\Facades\Event;
use LaravelRabbitmqNotificationChannel\Broker\Publisher;
use LaravelRabbitmqNotificationChannel\Event\RabbitMQNotificationFailed;
use LaravelRabbitmqNotificationChannel\Exception\BrokerConnectionException;
use LaravelRabbitmqNotificationChannel\Exception\RabbitMQChannelException;
use LaravelRabbitmqNotificationChannel\RabbitMQNotification;

final class RabbitMQChannel implements Channel
{
    public function __construct(private readonly Publisher $publisher)
    {
    }

    public function send($notifiable, RabbitMQNotification $notification): void
    {
        $message = $notification->toRabbitMQ($notifiable);

        try {
            $this->publisher->publishMessage($message);
        } catch (BrokerConnectionException $exception) {
            Event::dispatch(new RabbitMQNotificationFailed($exception));
            throw new RabbitMQChannelException("Message publishing failed", previous: $exception);
        }
    }
}
