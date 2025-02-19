<?php

namespace Xplore\Dbal;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    public function __construct(private Configuration $config)
    {
    }

    public function create()
    {
        $dbConfig = config('connections')[config('default')];

        return DriverManager::getConnection($dbConfig, $this->config);
    }
}