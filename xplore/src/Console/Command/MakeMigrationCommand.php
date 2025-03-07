<?php

namespace Xplore\Console\Command;

use Xplore\Console\StubLoader;

class MakeMigrationCommand implements CommandInterface
{
    public string $name = 'make:migration';

    public function execute(array $params = []): int
    {
        $migrationsDir = BASE_PATH . '/database/migrations';

        $timestamp = date('Y_m_d_His');
        $className = $timestamp . '_' . $params['resource'];
        $filename = "$migrationsDir/{$className}.php";

        $stubPath = __DIR__ . '/../../stubs/migration.stub';
        $content = StubLoader::loadStub($stubPath);

        file_put_contents($filename, $content);

        echo "Migration file created: $filename\n";

        return 0;
    }

}