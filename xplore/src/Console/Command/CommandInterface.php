<?php

namespace Xplore\Console\Command;

interface CommandInterface
{
    public function execute(array $params = []): int;
}