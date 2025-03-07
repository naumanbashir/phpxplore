<?php

namespace Xplore\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Xplore\Console\Traits\MigrationService;

class MigrateDatabase implements CommandInterface
{
    use MigrationService;

    public string $name = 'database:migrations:migrate';

    public function __construct(private Connection $connection)
    {
        $migrationsConfig = config('database.migrations');

        dd($migrationsConfig);
    }

    public function execute(array $params = []): int
    {
        try {
            $this->createMigrationsTable();

            /** -------------- Start Migrations Transaction -------------- */
            $this->connection->beginTransaction();

            $appliedMigrations = $this->getAppliedMigrations();
            $migrationFiles = $this->getMigrationFiles();
            $migrationsToApply = array_diff($migrationFiles, $appliedMigrations);

            /** Create Migrations SQL */
            $schema = new Schema();
            foreach ($migrationsToApply as $migration) {
                $migrationObject = require $this->migrationsPath . '/' . $migration;
                $migrationObject->up($schema);
            }

            /** Execute the SQL query */
            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }

            $this->connection->commit();
            /** -------------- End Migrations Transaction -------------- */

            return 0;

        } catch (\Throwable $throwable) {
            $this->connection->rollBack();
            throw $throwable;
        }
    }
}