<?php

namespace Jahir\Framework\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class MigrateDatabase implements CommandInterface
{
    private string $name = 'database:migrations:migrate';

    public function __construct(private Connection $connection)
    {
    }

    public function execute(array $params = []): int
    {
        try {
            // Create a migrations table SQL if table not already in existence
            $this->createMigrationsTable();

            $this->connection->beginTransaction();

            // Get $appliedMigrations which are already in the database.migrations table

            // Get the $migrationFiles from the migrations folder

            // Get the migrations to apply. i.e. they are in $migrationFiles but not in $appliedMigrations

            // Create SQL for any migrations which have not been run ..i.e. which are not in the database

            // Add migration to database

            // Execute the SQL query
            $this->connection->commit();

            echo 'Executing MigrateDatabase command' . PHP_EOL;

            return 0;
        } catch (\Throwable $throwable) {
            $this->connection->rollBack();
            throw $throwable;
        }
    }

    private function createMigrationsTable()
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ( !$schemaManager->tablesExist('migrations')) {
            $schema = new Schema();
            $table = $schema->createTable('migrations');
            $table->addColumn('id', Types::INTEGER, ['unsigned' => true, 'autoincrement' => true]);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlArray[0]);

            echo 'Migrations table created'.PHP_EOL;
        }
    }
}