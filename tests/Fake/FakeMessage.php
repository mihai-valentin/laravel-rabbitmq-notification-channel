<?php

declare(strict_types=1);

namespace Tests\Fake;

use LaravelRabbitmqNotificationChannel\Message\Message;

final class FakeMessage implements Message
{
    public function __construct(private readonly string $content)
    {
    }

    public function getPayload(): array
    {
        return ['content' => $this->content];
    }
}
