<?php

namespace Emma\App\Connection;

use Emma\App\Config;
use Emma\Common\Singleton\Singleton;
use Emma\Dbal\Connection\ConnectionProperty;
use Emma\Dbal\Connection\PDOConnection;
use Emma\Dbal\ConnectionManager;
use Emma\ORM\Connection\Connection as ORMConnection;

class DbConnection extends ORMConnection
{
    use Singleton;

    /**
     * @param ConnectionProperty|array $connectionDetails
     * @return void
     */
    public function connect(ConnectionProperty|array $connectionDetails): void
    {
        $this->setActiveConnection(self::createConnectionByModule());
    }

    /**
     * @param string|null $server
     * @return array
     */
    public static function getConnectionArrayFromConfig(string $server = null): array
    {
        $modulesConfig = Config::getDatabaseConfig();
        $server = $server ?? $modulesConfig["connection_pool"]["default"];
        return $modulesConfig["connection_pool"][$server];
    }

    /**
     * @param string|null $server
     * @return ConnectionProperty
     */
    public static function getConnectionPropertyFromConfig(string $server = null): ConnectionProperty
    {
        $connectTo = self::getConnectionArrayFromConfig($server);
        return ConnectionProperty::create($connectTo);
    }

    /**
     * @return PDOConnection
     */
    public static function createConnectionByModule(): PDOConnection
    {
        $ConnectionProperty = self::getConnectionPropertyFromConfig();
        return ConnectionManager::getConnection($ConnectionProperty);
    }

}