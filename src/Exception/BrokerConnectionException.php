<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications\Exception;

use LaravelRabbitmqNotifications\ErrorCodes\BrokerConnectionErrorCodes;
use Throwable;

final class BrokerConnectionException extends RabbitMQNotificationsException
{
    public function __construct(BrokerConnectionErrorCodes $errorCode, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($errorCode->value, $code, $previous);
    }
}
