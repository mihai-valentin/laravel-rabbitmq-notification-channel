<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use LaravelRabbitmqNotificationChannel\Broker\RabbitMQConnection;
use LaravelRabbitmqNotificationChannel\Broker\RabbitMQPublisher;
use LaravelRabbitmqNotificationChannel\Channel\RabbitMQChannel;
use LaravelRabbitmqNotificationChannel\Event\RabbitMQNotificationFailed;
use LaravelRabbitmqNotificationChannel\Exception\BrokerConnectionException;
use LaravelRabbitmqNotificationChannel\Exception\RabbitMQChannelException;
use LaravelRabbitmqNotificationChannel\Mapper\RabbitMQMessageMapper;
use LaravelRabbitmqNotificationChannel\Message\Message;
use Orchestra\Testbench\TestCase;
use Tests\Fake\FakeEmptyMessage;
use Tests\Fake\FakeMessage;
use Tests\Fake\FakeNotifiable;
use Tests\Fake\FakeRabbitMQConnection;
use Tests\Fake\FakeRabbitMQNotification;
use Tests\Fake\FakeUnopenableRabbitMQConnection;

final class RabbitMQChannelTest extends TestCase
{
    /**
     * @dataProvider messages
     */
    public function testWillSendRabbitMQNotificationSuccessfully(Message $message): void
    {
        $notification = new FakeRabbitMQNotification($message);

        $rabbitMQConnection = new FakeRabbitMQConnection();
        $rabbitMQPublisher = $this->getPublisher($rabbitMQConnection);
        (new RabbitMQChannel($rabbitMQPublisher))->send(new FakeNotifiable(), $notification);

        $publishedAMQPMessage = $rabbitMQConnection->getMessages()[0];
        $publishedAMQPMessageBody = $publishedAMQPMessage->getBody();
        $publishedAMQPMessageDecodedBody = json_decode($publishedAMQPMessageBody, true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals($publishedAMQPMessageDecodedBody, $message->getPayload());
    }

    private function messages(): array
    {
        return [
            [new FakeMessage('test')],
            [new FakeEmptyMessage()],
        ];
    }

    public function testWillDispatchRabbitMQNotificationFailedEventWhileSendingNotificationUsingBrokenConnection(): void
    {
        Event::fake();

        $this->expectException(RabbitMQChannelException::class);

        $notification = new FakeRabbitMQNotification(new FakeEmptyMessage());

        $rabbitMQPublisher = $this->getPublisher(new FakeUnopenableRabbitMQConnection());

        try {
            (new RabbitMQChannel($rabbitMQPublisher))->send(new FakeNotifiable(), $notification);
            $exceptionThrown = false;
        } catch (BrokerConnectionException) {
            $exceptionThrown = true;
        }

        $this->assertFalse($exceptionThrown);

        Event::assertDispatched(RabbitMQNotificationFailed::class);
    }

    public function getPublisher(RabbitMQConnection $rabbitMQConnection): RabbitMQPublisher
    {
        return new RabbitMQPublisher('queue', $rabbitMQConnection, new RabbitMQMessageMapper());
    }
}
