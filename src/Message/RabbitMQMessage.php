<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Message;

final class RabbitMQMessage implements Message
{
    public function __construct(
        private readonly string $channel,
        private readonly string $subject,
        private readonly string $content,
        private readonly string $receiver,
    ) {
    }

    public function getPayload(): array
    {
        return [
            'channel' => $this->channel,
            'title' => $this->subject,
            'text' => $this->content,
            'receiver' => $this->receiver,
        ];
    }
}
