<?php

namespace API\Models;

use API\Lib\Container;
use API\Lib\Interfaces\Models\IConnectionInterface;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;

class Connection implements IConnectionInterface {

    /**
     *
     * @param Container $container
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    private $connection;

    function __construct(array $config) {
        $manager = new ConnectionManagerSingle();
        $manager->setConfiguration($config);
        $manager->setName('default');

        $serviceContainer = Propel::getServiceContainer();
        $serviceContainer->checkVersion('2.0.0-dev');
        $serviceContainer->setAdapterClass('default', $config['adapter']);
        $serviceContainer->setConnectionManager('default', $manager);
        $serviceContainer->setDefaultDatasource('default');

        // Disable pooling! Will create problems otherways..
        Propel::disableInstancePooling();

        $this->connection = $serviceContainer->getConnection();
    }

    public function beginTransaction(): boolean
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): boolean
    {
        return $this->connection->commit();
    }

    public function inTransaction(): bool
    {
        return $this->connection->inTransaction();
    }

    public function rollBack(): boolean
    {
        return $this->connection->rollBack();
    }
}