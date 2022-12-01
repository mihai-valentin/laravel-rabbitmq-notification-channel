<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Console;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;

final class RabbitMQNotificationMakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    protected $name = 'make:notification:rabbitmq';

    protected static $defaultName = 'make:notification:rabbitmq';

    protected $description = 'Create a new RabbitMQ notification class';

    protected $type = 'RabbitMQ Notification';

    protected function getStub(): string
    {
        return __DIR__ . '/../../stubs/rabbitmq_notification.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Notifications';
    }
}
