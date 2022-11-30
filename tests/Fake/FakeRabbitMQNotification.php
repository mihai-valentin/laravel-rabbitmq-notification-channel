<?php

declare(strict_types=1);

namespace Tests\Fake;

use Illuminate\Notifications\Notification;
use LaravelRabbitmqNotificationChannel\Message\Message;
use LaravelRabbitmqNotificationChannel\RabbitMQNotification;

final class FakeRabbitMQNotification extends Notification implements RabbitMQNotification
{
    public function __construct(public readonly Message $message)
    {
    }

    public function via($notifiable): array
    {
        return ['rabbitmq'];
    }

    public function toRabbitMQ($notifiable): Message
    {
        return $this->message;
    }
}
