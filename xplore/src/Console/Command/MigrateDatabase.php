<?php

namespace Xplore\Console\Command;

class MigrateDatabase implements CommandInterface
{
    public string $name = 'migrate';

    public function execute(array $params = []): int
    {
        echo 'Executing MigrateDatabase command' . PHP_EOL;

        return 0;
    }

}