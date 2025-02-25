<?php

namespace Xplore\Console\Command;

class MakeMigrationCommand implements CommandInterface
{
    public string $name = 'make:migration';

    public function execute(array $params = []): int
    {
        echo 'Executing MigrateDatabase command' . PHP_EOL;

        return 0;
    }

}