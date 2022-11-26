<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications\Data;

final class MessageData
{
    public function __construct(
        public readonly string $channel,
        public readonly string $subject,
        public readonly string $content,
        public readonly string $receiver,
    ) {
    }
}
