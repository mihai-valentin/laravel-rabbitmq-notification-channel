<?php

declare(strict_types=1);

namespace Tests\Fake;

use LaravelRabbitmqNotificationChannel\Message\Message;
use LaravelRabbitmqNotificationChannel\RabbitMQNotification;

final class FakeRabbitMQNotification implements RabbitMQNotification
{
    public function __construct(public readonly Message $message)
    {
    }

    public function toRabbitMQ($notifiable): Message
    {
        return $this->message;
    }
}
