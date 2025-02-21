<?php

namespace Xplore\Console;

use Psr\Container\ContainerInterface;
use Xplore\Console\Command\CommandInterface;

final class Kernel
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function handle(): int
    {
        $this->registerCommands();
        return 0;
    }

    private function registerCommands(): void
    {
        $commandFiles = new \DirectoryIterator(__DIR__ . '/Command');

        $namespace = $this->container->get('base-commands-namespace');

        foreach ($commandFiles as $commandFile) {

            if (!$commandFile->isFile()) {
                continue;
            }

            $command = $namespace.pathinfo($commandFile, PATHINFO_FILENAME);

            if (is_subclass_of($command, CommandInterface::class)) {
                // Add to the container, using the name as the ID e.g. $container->add('database:migrations:migrate', MigrateDatabase::class)
                $commandName = (new \ReflectionClass($command))->getProperty('name')->getDefaultValue();

                $this->container->add($commandName, $command);
            }
        }
    }
}