<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications\Channel;

use LaravelRabbitmqNotifications\Broker\RabbitMQPublisher;
use LaravelRabbitmqNotifications\Exception\RabbitMQNotificationsException;
use LaravelRabbitmqNotifications\Data\MessageData;
use LaravelRabbitmqNotifications\RabbitMQNotification;
use Illuminate\Support\Facades\Log;

final class RabbitMQNotificationsChannel
{
    public function __construct(
        private readonly RabbitMQPublisher $publisher,
        private readonly int $retriesCount = 0,
    ) {
    }

    public function send($notifiable, RabbitMQNotification $notification): void
    {
        $message = $notification->toNotificationsMicroservice($notifiable);

        $sent = $this->sendWithRetries($message);

        if (!$sent) {
            Log::error('Cannot notify user', [
                'user_id' => $notifiable->id,
                'user_email' => $notifiable->email,
            ]);
        }
    }

    private function sendWithRetries(MessageData $message): bool
    {
        for ($attempt = 0; $attempt < $this->retriesCount; $attempt++) {
            $sent = $this->attempt($message);

            if ($sent) {
                return true;
            }
        }

        return false;
    }

    private function attempt(MessageData $message): bool
    {
        try {
            $this->publisher->publishMessage($message);
            return true;
        } catch (RabbitMQNotificationsException) {
            return false;
        }
    }
}
