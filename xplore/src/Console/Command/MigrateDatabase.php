<?php

namespace Xplore\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class MigrateDatabase implements CommandInterface
{
    public string $name = 'database:migrations:migrate';


    public function __construct(
        private Connection $connection,
        private string $migrationsPath
    )
    {
    }

    public function execute(array $params = []): int
    {
        try {
            $this->createMigrationsTable();

            // Start migration transaction
            $this->connection->beginTransaction();

            $appliedMigrations = $this->getAppliedMigrations();

            $migrationFiles = $this->getMigrationFiles();

            $migrationsToApply = array_diff($migrationFiles, $appliedMigrations);

            $schema = new Schema();

            foreach ($migrationsToApply as $migration) {

                $migrationObject = require $this->migrationsPath . '/' . $migration;

                // call up method
                $migrationObject->up($schema);

                // Add migration to database
            }

            $this->connection->commit();

            return 0;

        } catch (\Throwable $throwable) {

            $this->connection->rollBack();

            throw $throwable;
        }
    }

    private function getMigrationFiles(): array
    {
        $migrationFiles = scandir($this->migrationsPath);

        $filteredFiles = array_filter($migrationFiles, function($file) {
            return !in_array($file, ['.', '..']);
        });

        return $filteredFiles;
    }

    private function getAppliedMigrations(): array
    {
        $sql = "SELECT migration FROM migrations;";

        $appliedMigrations = $this->connection->executeQuery($sql)->fetchFirstColumn();

        return $appliedMigrations;
    }

    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['migrations'])) {
            $schema = new Schema();
            $table = $schema->createTable('migrations');
            $table->addColumn('id', Types::INTEGER, ['unsigned' => true, 'autoincrement' => true]);
            $table->addColumn('migration', Types::STRING, ['length' => 255]);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
            $table->setPrimaryKey(['id']);

            $sqlQueries = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlQueries[0]);

            echo 'migrations table created' . PHP_EOL;
        }
    }

}