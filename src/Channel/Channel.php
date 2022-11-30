<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Channel;

use LaravelRabbitmqNotificationChannel\RabbitMQNotification;

interface Channel
{
    public function send($notifiable, RabbitMQNotification $notification): void;
}
