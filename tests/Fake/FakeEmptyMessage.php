<?php

declare(strict_types=1);

namespace Tests\Fake;

use LaravelRabbitmqNotificationChannel\Message\Message;

final class FakeEmptyMessage implements Message
{
    public function getPayload(): array
    {
        return [];
    }
}
