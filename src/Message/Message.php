<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Message;

interface Message
{
    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array;
}
