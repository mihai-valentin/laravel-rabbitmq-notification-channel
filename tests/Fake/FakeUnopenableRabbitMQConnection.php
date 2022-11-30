<?php

declare(strict_types=1);

namespace Tests\Fake;

use LaravelRabbitmqNotificationChannel\Broker\RabbitMQConnection;
use LaravelRabbitmqNotificationChannel\Exception\CannotOpenBrokerConnectionException;
use PhpAmqpLib\Message\AMQPMessage;

final class FakeUnopenableRabbitMQConnection extends FakeRabbitMQConnection
{
    protected function openConnection(): void
    {
        throw new CannotOpenBrokerConnectionException();
    }
}
