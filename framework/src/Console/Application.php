<?php

namespace Jahir\Framework\Console;

use Jahir\Framework\Console\Exception\CommandException;
use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(private  ContainerInterface $container)
    {
    }

    public function run(): int
    {
        // Use environment variables to obtain the command name
        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        // Throw an exception if no command name is provided
        if ($commandName == null) {
            throw new CommandException('Command string is missing !!');
        }

        // Use command name to obtain a command object from the container
         $command = $this->container->get($commandName);

        // Parse variables to obtain options and args
        $argv = array_slice($argv, 2);

        $options = $this->parseOptions($argv);

        // Execute the command, returning the status code
        $status = $command->execute($options);

        // Return the status code
        return $status;
    }

    private function parseOptions(array $argvs): array
    {
        $options = [];

        foreach ($argvs as $arg) {
            if (str_starts_with($arg, '--')) {
                $option = explode('=',substr($arg, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }

        return $options;
    }
}