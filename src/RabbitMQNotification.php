<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel;

use LaravelRabbitmqNotificationChannel\Message\Message;

interface RabbitMQNotification
{
    public function toNotificationsMicroservice($notifiable): Message;
}
