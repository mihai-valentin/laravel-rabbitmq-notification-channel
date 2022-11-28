<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Mapper;

use LaravelRabbitmqNotificationChannel\Message\Message;
use PhpAmqpLib\Message\AMQPMessage;

interface MessageMapper
{
    public function mapToAMQPMessage(Message $message): AMQPMessage;
}
