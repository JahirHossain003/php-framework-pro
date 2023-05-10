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

        // Execute the command, returning the status code
         $status = $command->execute();

        // Return the status code
        return $status;
    }
}