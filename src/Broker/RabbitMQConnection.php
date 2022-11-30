<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Broker;

use LaravelRabbitmqNotificationChannel\Exception\BrokerConnectionException;
use Exception;
use LaravelRabbitmqNotificationChannel\Exception\CannotCloseBrokerConnectionException;
use LaravelRabbitmqNotificationChannel\Exception\CannotOpenBrokerConnectionException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * @codeCoverageIgnore
 */
class RabbitMQConnection
{
    private AMQPStreamConnection $connection;

    public function __construct(
        protected readonly string $host,
        protected readonly string $port,
        protected readonly string $user,
        protected readonly string $password,
    ) {
    }

    /**
     * @throws BrokerConnectionException
     */
    public function publishMessage(AMQPMessage $message, string $queueName): void
    {
        $this->openConnection();

        $channel = $this->connection->channel();

        $this->declareDurableQueue($channel, $queueName);
        $this->publishBasicMessage($channel, $message, $queueName);

        $channel->close();

        $this->closeConnection();
    }

    /**
     * @throws CannotOpenBrokerConnectionException
     */
    protected function openConnection(): void
    {
        try {
            $this->connection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->password,
            );
        } catch (Exception) {
            throw new CannotOpenBrokerConnectionException();
        }
    }

    /**
     * @throws CannotCloseBrokerConnectionException
     */
    protected function closeConnection(): void
    {
        try {
            $this->connection->close();
        } catch (Exception) {
            throw new CannotCloseBrokerConnectionException();
        }
    }

    protected function declareDurableQueue(AMQPChannel $channel, string $name): void
    {
        $channel->queue_declare($name, durable: true, auto_delete: false);
    }

    protected function publishBasicMessage(AMQPChannel $channel, AMQPMessage $message, string $queueName): void
    {
        $channel->basic_publish($message, routing_key: $queueName);
    }
}
