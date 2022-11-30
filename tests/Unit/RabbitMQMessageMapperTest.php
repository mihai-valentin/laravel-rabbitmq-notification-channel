<?php

declare(strict_types=1);

namespace Tests\Unit;

use LaravelRabbitmqNotificationChannel\Mapper\RabbitMQMessageMapper;
use LaravelRabbitmqNotificationChannel\Message\Message;
use PHPUnit\Framework\TestCase;
use Tests\Fake\FakeEmptyMessage;
use Tests\Fake\FakeMessage;

final class RabbitMQMessageMapperTest extends TestCase
{
    private RabbitMQMessageMapper $rabbitMqMessageMapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rabbitMqMessageMapper = new RabbitMQMessageMapper();
    }

    /**
     * @dataProvider messages
     */
    public function testWillMapMessageToAMQPMessageSuccessfully(Message $message): void
    {
        $amqpMessage = $this->rabbitMqMessageMapper->mapToAMQPMessage($message);
        $amqpMessageBody = $amqpMessage->getBody();
        $amqpMessageDecodeBody = json_decode($amqpMessageBody, true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals($amqpMessageDecodeBody, $message->getPayload());
    }

    private function messages(): array
    {
        return [
            [new FakeMessage('test')],
            [new FakeEmptyMessage()],
        ];
    }
}
