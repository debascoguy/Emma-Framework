<?php

namespace Emma\App\Connection;

use Emma\App\Config;
use Emma\Common\Singleton\Singleton;
use Emma\Dbal\Connection\ConnectionProperty;
use Emma\Dbal\Connection\PDOConnection;
use Emma\Dbal\ConnectionManager;

class DbConnection
{
    use Singleton;

    /**
     * @param string|null $server
     * @return array
     */
    public static function getConnectionInfoFromConfig(string $server = null): array
    {
        $dbConfigs = Config::getDatabaseConfig();
        $server = $server ?? $dbConfigs["connection_pool"]["default"];
        return $dbConfigs["connection_pool"][$server];
    }

    /**
     * @param string|null $server
     * @return ConnectionProperty
     */
    public static function getConnectionPropertyFromConfig(string $server = null): ConnectionProperty
    {
        $connectTo = self::getConnectionInfoFromConfig($server);
        return ConnectionProperty::create($connectTo);
    }

    /**
     * @return PDOConnection
     */
    public static function createDefaultConnection(): PDOConnection
    {
        $ConnectionProperty = self::getConnectionPropertyFromConfig();
        return ConnectionManager::getConnection($ConnectionProperty);
    }

}