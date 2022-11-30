<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Event;

use LaravelRabbitmqNotificationChannel\Exception\BrokerConnectionException;

final class RabbitMQNotificationFailed
{
    public function __construct(public readonly BrokerConnectionException $exception)
    {
    }
}
