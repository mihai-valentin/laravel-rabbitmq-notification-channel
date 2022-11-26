<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications\Mapper;

use LaravelRabbitmqNotifications\Data\MessageData;
use PhpAmqpLib\Message\AMQPMessage;

interface MessageDataMapperInterface
{
    public function mapMessageDataToAMQPMessage(MessageData $messageData): AMQPMessage;
}
