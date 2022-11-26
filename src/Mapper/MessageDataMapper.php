<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications\Mapper;

use LaravelRabbitmqNotifications\Data\MessageData;
use PhpAmqpLib\Message\AMQPMessage;

final class MessageDataMapper implements MessageDataMapperInterface
{
    public function mapMessageDataToAMQPMessage(MessageData $messageData): AMQPMessage
    {
        $payload = json_encode([
            'channel'  => $messageData->channel,
            'title'    => $messageData->subject,
            'text'     => $messageData->content,
            'receiver' => $messageData->receiver,
        ], JSON_THROW_ON_ERROR);

        return new AMQPMessage($payload);
    }
}
