<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Console;

use Illuminate\Console\Command;

final class PublishCommand extends Command
{
    protected $signature = 'rabbitmq-notification-channel:publish {--force : Overwrite any existing files}';

    protected $description = 'Publish the RabbitMQ Notification Channel Configuration';

    public function handle(): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'rabbitmq-notification-channel',
            '--force' => $this->option('force')
        ]);
    }
}
