<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Broker;

use LaravelRabbitmqNotificationChannel\Message\Message;

interface Publisher
{
    public function publishMessage(Message $message): void;
}
