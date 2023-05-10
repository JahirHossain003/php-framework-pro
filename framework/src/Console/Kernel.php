<?php

namespace Jahir\Framework\Console;

use Jahir\Framework\Console\Command\CommandInterface;
use Psr\Container\ContainerInterface;

class Kernel
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function handle(): int
    {
        // Register commands with the container
        $this->registerCommands();

        // Run the console application, returning a status code

        // return the status code
        return 0;
    }

    private function registerCommands()
    {
        // === Register All Built In Commands ===
        // Get all files in the Commands dir
        $commandFiles = new \DirectoryIterator(__DIR__.'/Command');

        $commandNamespace = $this->container->get('base-command-namespace');

        // Loop over all files in the commands folder
        foreach ($commandFiles as $commandFile) {
            if (!$commandFile->isFile()) {
                continue;
            }

            // Get the Command class name..using psr4 this will be same as filename
            $command = $commandNamespace.pathinfo($commandFile,PATHINFO_FILENAME);

            // If it is a subclass of CommandInterface
            if (is_subclass_of($command, CommandInterface::class)) {
                $commandName = (new \ReflectionClass($command))->getProperty('name')->getDefaultValue();
                $this->container->add($commandName, $command);
            }
        }

        // === Register all user-defined commands (@todo) ===
    }

}