<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications\Broker;

use LaravelRabbitmqNotifications\ErrorCodes\BrokerConnectionErrorCodes;
use LaravelRabbitmqNotifications\Exception\BrokerConnectionException;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class RabbitMQConnection
{
    private AMQPStreamConnection $connection;

    public function __construct(
        private readonly string $host,
        private readonly string $port,
        private readonly string $user,
        private readonly string $password,
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
     * @throws BrokerConnectionException
     */
    private function openConnection(): void
    {
        try {
            $this->connection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->password,
            );
        } catch (Exception) {
            throw new BrokerConnectionException(
                BrokerConnectionErrorCodes::CANNOT_CONNECT
            );
        }
    }

    /**
     * @throws BrokerConnectionException
     */
    private function closeConnection(): void
    {
        try {
            $this->connection->close();
        } catch (Exception) {
            throw new BrokerConnectionException(
                BrokerConnectionErrorCodes::CANNOT_CLOSE_CONNECTION
            );
        }
    }

    private function declareDurableQueue(AMQPChannel $channel, string $name): void
    {
        $channel->queue_declare($name, durable: true, auto_delete: false);
    }

    private function publishBasicMessage(AMQPChannel $channel, AMQPMessage $message, string $queueName): void
    {
        $channel->basic_publish($message, routing_key: $queueName);
    }
}
