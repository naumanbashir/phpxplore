<?php

namespace Xplore\Dbal;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    public function create()
    {

        $dbConfig = config('connections')[config('default')];

        try {
            $connection = DriverManager::getConnection($dbConfig);

        } catch (\Doctrine\DBAL\Exception $e) {
            throw new \RuntimeException('Connection failed: ' . $e->getMessage());
        }

        return $connection;
    }
}