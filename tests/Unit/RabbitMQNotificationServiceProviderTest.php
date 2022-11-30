<?php

declare(strict_types=1);

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use LaravelRabbitmqNotificationChannel\Channel\RabbitMQChannel;
use LaravelRabbitmqNotificationChannel\Message\Message;
use LaravelRabbitmqNotificationChannel\RabbitMQNotification;
use LaravelRabbitmqNotificationChannel\RabbitMQNotificationServiceProvider;
use Orchestra\Testbench\TestCase;
use Tests\Fake\FakeMessage;
use Tests\Fake\FakeNotifiable;

final class RabbitMQNotificationServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            RabbitMQNotificationServiceProvider::class,
        ];
    }

    public function test(): void
    {
        NotificationFacade::fake();

        $notification = new class extends Notification implements RabbitMQNotification
        {
            public function via($notifiable): array
            {
                return [RabbitMQChannel::class];
            }

            public function toRabbitMQ($notifiable): Message
            {
                return new FakeMessage('test');
            }
        };

        $notifiable = new FakeNotifiable();

        NotificationFacade::send($notifiable, $notification);
        NotificationFacade::assertSentTo($notifiable, get_class($notification));
    }
}
