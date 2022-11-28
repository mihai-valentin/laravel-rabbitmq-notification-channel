<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Message;

interface Message
{
    public function getPayload(): array;
}
