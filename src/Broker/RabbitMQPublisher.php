<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications\Broker;

use LaravelRabbitmqNotifications\Data\MessageData;
use LaravelRabbitmqNotifications\Mapper\MessageDataMapperInterface;

final class RabbitMQPublisher
{
    public function __construct(
        private readonly RabbitMQConnection $connection,
        private readonly string $queueName,
        private readonly MessageDataMapperInterface $messageDataMapper,
    ) {
    }

    public function publishMessage(MessageData $messageData): void
    {
        $AMQPMessage = $this->messageDataMapper->mapMessageDataToAMQPMessage($messageData);

        $this->connection->publishMessage($AMQPMessage, $this->queueName);
    }
}
