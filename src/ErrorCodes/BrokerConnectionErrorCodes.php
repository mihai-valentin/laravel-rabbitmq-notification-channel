<?php

declare(strict_types=1);

namespace LaravelRabbitmqNotifications\ErrorCodes;

enum BrokerConnectionErrorCodes: string
{
    case CANNOT_CONNECT = 'broker.cannot_connect';
    case CANNOT_CLOSE_CONNECTION = 'broker.cannot_close_connection';
}
