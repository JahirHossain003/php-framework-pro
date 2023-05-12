<?php

namespace Jahir\Framework\Console\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

class MigrateDatabase implements CommandInterface
{
    private string $name = 'database:migrations:migrate';

    public function __construct(
        private Connection $connection,
        private string $migrationFilePath
    )
    {
    }

    public function execute(array $params = []): int
    {
        try {
            // Create a migrations table SQL if table not already in existence
            $this->createMigrationsTable();

            $this->connection->beginTransaction();

            // Get $appliedMigrations which are already in the database.migrations table
            $appliedMigrations = $this->getAppliedMigrations();

            // Get the $migrationFiles from the migrations folder
            $migrationFiles = $this->getMigrationFiles();

            // Get the migrations to apply. i.e. they are in $migrationFiles but not in $appliedMigrations
            $migrations = array_diff($migrationFiles, $appliedMigrations);

            $schema = new Schema();

            foreach ($migrations as $migration) {
                // Create SQL for any migrations which have not been run ..i.e. which are not in the database
                $migrationClass = require_once $this->migrationFilePath.'/'.$migration;

                $migrationClass->up($schema);

                // Add migration to database
                $this->insertMigration($migration);
            }

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }

            // Execute the SQL query
            $this->connection->commit();

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

    private function getAppliedMigrations(): array
    {
        $sql = "Select migration from migrations";

        $appliedMigrations = $this->connection->executeQuery($sql)->fetchFirstColumn();

        return $appliedMigrations;
    }

    private function getMigrationFiles()
    {
        $files = scandir($this->migrationFilePath);
        $files = array_filter($files, function ($file) {
           return !in_array($file, ['.','..']);
        });
        return $files;
    }

    private function insertMigration(string $migration): void
    {
        $sql = "INSERT INTO migrations (migration) VALUES(?)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(1, $migration);

        $stmt->executeStatement();
    }
}