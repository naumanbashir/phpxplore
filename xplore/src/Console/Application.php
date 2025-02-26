<?php

namespace Xplore\Console;

use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function run(): int
    {
        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        if (!$commandName) {
            throw new ConsoleException('A command name must be provided');
        }

        $command = $this->container->get($commandName);

        $args = array_slice($argv, 2);

        $options = $this->parseOptions($args);

        $status = $command->execute($options);

        return $status;
    }

    private function parseOptions(array $args): array
    {
        $options = [];

        foreach ($args as $arg) {
            if (str_starts_with($arg, '--')) {
                // This is an option
                $option = explode('=', substr($arg, 2));
                $options[$option[0]] = $option[1] ?? true;
            } else {
                $options['resource'] = $arg;
            }
        }

        return $options;
    }
}