<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotificationChannel\Exception;

use RuntimeException;

abstract class BrokerConnectionException extends RuntimeException
{
}
