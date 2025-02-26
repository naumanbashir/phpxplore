<?php

namespace Xplore\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class MigrateDatabase implements CommandInterface
{
    public string $name = 'database:migrations:migrate';


    public function __construct(private Connection $connection)
    {
    }

    public function execute(array $params = []): int
    {
        $this->createMigrationsTable();

        return 0;
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