<?php

namespace Xplore\Console\Traits;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

trait MigrationService
{
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

    private function insertMigration(string $migration): void
    {
        $sql = "INSERT INTO migrations (migration) VALUES (?)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(1, $migration);

        $stmt->executeStatement();
    }
}