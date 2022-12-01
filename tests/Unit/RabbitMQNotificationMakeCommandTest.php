<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use LaravelRabbitmqNotificationChannel\Console\RabbitMQNotificationMakeCommand;
use LaravelRabbitmqNotificationChannel\RabbitMQNotificationServiceProvider;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\BufferedOutput;

final class RabbitMQNotificationMakeCommandTest extends TestCase
{
    private const TEST_NOTIFICATION_CLASS_NAME = 'TestNotification';

    private Filesystem $filesystem;
    private ArrayInput $input;
    private BufferedOutput $output;
    private RabbitMQNotificationMakeCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * @var Filesystem $filesystem
         */
        $this->filesystem = $this->app->get(Filesystem::class);

        $this->command = new RabbitMQNotificationMakeCommand($this->filesystem);
        $this->command->setLaravel($this->app);

        $this->input = new ArrayInput(
            ['name' => self::TEST_NOTIFICATION_CLASS_NAME],
            new InputDefinition([new InputArgument('name')])
        );

        $this->output = new BufferedOutput();
    }

    protected function tearDown(): void
    {
        $path = $this->app->basePath('app/Notifications/' . self::TEST_NOTIFICATION_CLASS_NAME . '.php');

        $this->filesystem->delete($path);

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            RabbitMQNotificationServiceProvider::class,
        ];
    }

    public function testWillMakeRabbitMqNotificationSuccessfully(): void
    {
        try {
            $this->command->run($this->input, $this->output);
            $errorThrown = false;
        } catch (Throwable) {
            $errorThrown = true;
        }

        $this->assertFalse($errorThrown);

        $successMessage = 'INFO  RabbitMQ Notification [app/Notifications/TestNotification.php] created successfully.';
        $this->assertTrue(str_contains($this->output->fetch(), $successMessage));
    }

    public function testWillMakeDuplicatedRabbitMqNotificationWithError(): void
    {
        try {
            $this->command->run($this->input, $this->output);
            $this->command->run($this->input, $this->output);
            $errorThrown = false;
        } catch (Throwable) {
            $errorThrown = true;
        }

        $this->assertFalse($errorThrown);

        $successMessage = 'ERROR  RabbitMQ Notification already exists.';
        $this->assertTrue(str_contains($this->output->fetch(), $successMessage));
    }
}
