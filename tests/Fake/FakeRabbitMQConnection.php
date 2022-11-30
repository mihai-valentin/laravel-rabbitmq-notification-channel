<?php

declare(strict_types=1);

namespace Tests\Fake;

use LaravelRabbitmqNotificationChannel\Broker\RabbitMQConnection;
use LaravelRabbitmqNotificationChannel\Message\Message;
use PhpAmqpLib\Message\AMQPMessage;

class FakeRabbitMQConnection extends RabbitMQConnection
{
    /**
     * @var AMQPMessage[]
     */
    private array $messages = [];

    public function __construct(
        string $host = 'host',
        string $port = 'port',
        string $user = 'user',
        string $password = 'password',
    ) {
        parent::__construct($host, $port, $user, $password);
    }

    public function publishMessage(AMQPMessage $message, string $queueName): void
    {
        $this->openConnection();
        $this->messages[] = $message;
        $this->closeConnection();
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    protected function openConnection(): void
    {
    }

    protected function closeConnection(): void
    {
    }
}
