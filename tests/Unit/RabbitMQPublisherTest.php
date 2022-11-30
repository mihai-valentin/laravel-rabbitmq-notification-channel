<?php

declare(strict_types=1);

use LaravelRabbitmqNotificationChannel\Broker\RabbitMQConnection;
use LaravelRabbitmqNotificationChannel\Broker\RabbitMQPublisher;
use LaravelRabbitmqNotificationChannel\Exception\BrokerConnectionException;
use LaravelRabbitmqNotificationChannel\Mapper\RabbitMQMessageMapper;
use PHPUnit\Framework\TestCase;
use Tests\Fake\FakeMessage;
use Tests\Fake\FakeRabbitMQConnection;
use Tests\Fake\FakeUncloseableRabbitMQConnection;
use Tests\Fake\FakeUnopenableRabbitMQConnection;

final class RabbitMQPublisherTest extends TestCase
{
    private const DEFAULT_QUEUE = 'queue';

    private RabbitMQMessageMapper $messageMapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->messageMapper = new RabbitMQMessageMapper();
    }

    public function testWillPublishMessageSuccessfully(): void
    {
        $message = new FakeMessage('test');
        $rabbitMQConnection = new FakeRabbitMQConnection();

        try {
            $this
                ->getPublisher($rabbitMQConnection)
                ->publishMessage($message)
            ;

            $exceptionThrown = false;
        } catch (BrokerConnectionException) {
            $exceptionThrown = true;
        }

        $this->assertFalse($exceptionThrown);

        $publishedAMQPMessage = $rabbitMQConnection->getMessages()[0];
        $publishedAMQPMessageBody = $publishedAMQPMessage->getBody();
        $publishedAMQPMessageDecodedBody = json_decode($publishedAMQPMessageBody, true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals($publishedAMQPMessageDecodedBody, $message->getPayload());
    }

    public function testWillThrowCannotOpenConnectionBrokerExceptionWhilePublishingMessageUsingBrokenConnection(): void
    {
        $message = new FakeMessage('test');
        $rabbitMQConnection = new FakeUnopenableRabbitMQConnection();

        try {
            $this
                ->getPublisher($rabbitMQConnection)
                ->publishMessage($message)
            ;

            $exceptionThrown = false;
        } catch (BrokerConnectionException) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testWillThrowCannotCloseConnectionBrokerExceptionWhilePublishingMessageUsingBrokenConnection(): void
    {
        $message = new FakeMessage('test');
        $rabbitMQConnection = new FakeUncloseableRabbitMQConnection();

        try {
            $this
                ->getPublisher($rabbitMQConnection)
                ->publishMessage($message)
            ;

            $exceptionThrown = false;
        } catch (BrokerConnectionException) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    private function getPublisher(RabbitMQConnection $rabbitMQConnection): RabbitMQPublisher
    {
        return new RabbitMQPublisher(self::DEFAULT_QUEUE, $rabbitMQConnection, $this->messageMapper);
    }
}
