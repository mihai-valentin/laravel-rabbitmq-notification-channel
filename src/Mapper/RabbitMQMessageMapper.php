<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Mapper;

use LaravelRabbitmqNotificationChannel\Message\Message;
use PhpAmqpLib\Message\AMQPMessage;

final class RabbitMQMessageMapper implements MessageMapper
{
    public function mapToAMQPMessage(Message $message): AMQPMessage
    {
        $payloadJson = json_encode($message->getPayload(), JSON_THROW_ON_ERROR);

        return new AMQPMessage($payloadJson);
    }
}
