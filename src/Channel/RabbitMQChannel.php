<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Channel;

use LaravelRabbitmqNotificationChannel\Broker\RabbitMQPublisher;
use LaravelRabbitmqNotificationChannel\Exception\BrokerConnectionException;
use LaravelRabbitmqNotificationChannel\RabbitMQNotification;

final class RabbitMQChannel implements Channel
{
    public function __construct(private readonly RabbitMQPublisher $publisher)
    {
    }

    public function send($notifiable, RabbitMQNotification $notification): bool
    {
        $message = $notification->toNotificationsMicroservice($notifiable);

        try {
            $this->publisher->publishMessage($message);

            return true;
        } catch (BrokerConnectionException $exception) {
            // Event::dispatch(...);
        }

        return false;
    }
}
