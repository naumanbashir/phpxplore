<?php

namespace Xplore\Dbal;

use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    public function create()
    {
        try {
            $dbConfig = config('database');

            if (!isset($dbConfig['connections'][$dbConfig['default']])) {
                throw new \RuntimeException('Invalid database configuration: missing default connection settings.');
            }

            $dbConfig = $dbConfig['connections'][$dbConfig['default']];

            $connection = DriverManager::getConnection($dbConfig);

        } catch (\Doctrine\DBAL\Exception $e) {
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
        }

        return $connection;
    }
}