<?php

declare(strict_types=1);

namespace Tests\Fake;

use LaravelRabbitmqNotificationChannel\Broker\RabbitMQConnection;
use LaravelRabbitmqNotificationChannel\Exception\CannotCloseBrokerConnectionException;
use LaravelRabbitmqNotificationChannel\Exception\CannotOpenBrokerConnectionException;
use PhpAmqpLib\Message\AMQPMessage;

final class FakeUncloseableRabbitMQConnection extends FakeRabbitMQConnection
{
    protected function closeConnection(): void
    {
        throw new CannotCloseBrokerConnectionException();
    }
}
