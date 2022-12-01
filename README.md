# Laravel RabbitMQ Notification Channel

Simple Laravel RabbitMQ Notification Channel. Imagine you want to send a Laravel notification via rabbitmq. Now you can
use this simple package without caring about an individual channel and connections. Just create a message and send
via `rabbitmq` channel.

## Installation

Get the package

```shell
composer require mihai-valentin/laravel-rabbitmq-notification-channel
```

Publish configuration

```shell
php artisan rabbitmq-notification-channel:publish
```

> Note
> You can use `--force` option to overwrite existing files

## Configuration

To start using the `rabbitmq` channel you have to configure it. There are two ways of configuration using config file
and using environment variables.

The config file options:
 - `default_queue` - the name of the queue that for messages
 - `rabbitmq` - an array of RabbitMQ connection parameters (host, port, user and password)

Alternatively you can set all the config using your `.env` file:

```shell
RABBITMQ_NOTIFICATION_DEFAULT_QUEUE=queue
RABBITMQ_NOTIFICATION_HOST=localhost
RABBITMQ_NOTIFICATION_PORT=5672
RABBITMQ_NOTIFICATION_USER=guest
RABBITMQ_NOTIFICATION_PASSWORD=guest
```

## Instantaneously Start

If you have to create a notification from scratch, then just run an artisan command:

```shell
php artisan make:notification:rabbitmq
```

Then implement the `toRabbitMQ` method. Note that it must return a `LaravelRabbitmqNotificationChannel\Message\Message`
implementation.

## Quick Start

1. Add the `LaravelRabbitmqNotificationChannel\RabbitMQNotification` interface to your notification
2. Add `rabbitmq` channel to the notification's via array
3. Implement the `toRabbitMQ` method respecting the `LaravelRabbitmqNotificationChannel\RabbitMQNotification` interface
4. Implement the `LaravelRabbitmqNotificationChannel\Message\Message` interface and use the implementation in
   the `toRabbitMQ` method
5. Notify your notifiables

## Contracts

The `LaravelRabbitmqNotificationChannel\RabbitMQNotification` interface represents a generic contract for notifications.
It declares how to get a message to send it via the channel. In other words, if you have to send a notification using
the `rabbitmq` channel you have to implement this interface.

The `LaravelRabbitmqNotificationChannel\Message\Message` interface is a message abstraction. It declares the way of
providing its content. Every message that has to be sent via `rabbitmq` channel must implement it.

The `LaravelRabbitmqNotificationChannel\RabbitMQNotification\Channel` interface represents the rabbitmq notification
channel interface. You can use it to implement you own channel.

The `LaravelRabbitmqNotificationChannel\Broker\Publisher` interface represents the RabbitMQ messages publishing
contract. You can use it to overwrite default publisher.

The `LaravelRabbitmqNotificationChannel\Mapper\MessageMapper` interface tells about "Message to AMQPMessage" mapping
rules. You can also use it to overwrite the default mapper.

## RabbitMQ Connection

The `LaravelRabbitmqNotificationChannel\Broker\RabbitMQConnection` class represents basic RabbitMQ connection
abstraction. You can extend it to overwrite the message delivery scenario.

## Code of Conduct

## Contributing

## License
