<?php

declare(strict_types=1);

use LaravelRabbitmqNotificationChannel\RabbitMQNotificationServiceProvider;
use Orchestra\Testbench\TestCase;

final class PublishCommandTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            RabbitMQNotificationServiceProvider::class,
        ];
    }

    public function testWillPublishPackageConfigSuccessfully(): void
    {
        $this
            ->artisan('rabbitmq-notification-channel:publish')
            ->assertSuccessful()
        ;
    }

    public function testWillForcePackageConfigPublishingSuccessfully(): void
    {
        $this
            ->artisan('rabbitmq-notification-channel:publish', ['--force' => true])
            ->assertSuccessful()
        ;
    }
}
