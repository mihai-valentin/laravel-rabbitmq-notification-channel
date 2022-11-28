<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Broker;

use LaravelRabbitmqNotificationChannel\Mapper\MessageMapper;
use LaravelRabbitmqNotificationChannel\Message\Message;

final class RabbitMQPublisher implements Publisher
{
    public function __construct(
        private readonly string $defaultQueue,
        private readonly RabbitMQConnection $connection,
        private readonly MessageMapper $messageDataMapper,
    ) {
    }

    public function publishMessage(Message $message): void
    {
        $AMQPMessage = $this->messageDataMapper->mapToAMQPMessage($message);

        $this->connection->publishMessage($AMQPMessage, $this->defaultQueue);
    }
}
