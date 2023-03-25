<?php

declare(strict_types=1);

namespace Tests\Integration;

use Exception;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use LaravelRabbitmqNotificationChannel\RabbitMQNotificationServiceProvider;
use Orchestra\Testbench\TestCase;
use Tests\Fake\FakeMessage;
use Tests\Fake\FakeNotifiable;
use Tests\Fake\FakeRabbitMQNotification;

final class SendMessageUsingRealRabbitMQTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            RabbitMQNotificationServiceProvider::class,
        ];
    }

    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        $config = include __DIR__ . '/../../config/rabbitmq-notification-channel.php';

        $app['config']->set('rabbitmq-notification-channel', $config);
    }

    public function testWillSendNotificationUsingRealNotificationsSuccessfully(): void
    {
        $message = new FakeMessage('test');
        $notification = new FakeRabbitMQNotification($message);
        $notifiable = new FakeNotifiable();

        try {
            NotificationFacade::send($notifiable, $notification);
            $exceptionThrown = false;
        } catch (Exception) {
            $exceptionThrown = true;
        }

        self::assertFalse($exceptionThrown);
    }
}
