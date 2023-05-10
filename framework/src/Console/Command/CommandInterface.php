<?php

namespace Jahir\Framework\Console\Command;

interface CommandInterface
{
    public function execute(array $params = []): int;
}