<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications;

use LaravelRabbitmqNotifications\Data\MessageData;

interface RabbitMQNotification
{
    public function toNotificationsMicroservice($notifiable): MessageData;
}
