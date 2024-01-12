<?php

namespace Emma\App;

use Emma\App\Connection\DbConnection;
use Emma\App\ErrorHandler\ErrorHandlerScheduler;
use Emma\App\ErrorHandler\ExceptionHandlerScheduler;
use Emma\App\ErrorHandler\ShutDownHandlerScheduler;
use Emma\Di\Container\Container;
use Emma\Http\HttpManager;
use Emma\Http\RouteRegistry;
use Emma\ORM\Connection\Connection;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Boot
{

    /**
     * @param Core $core
     * @throws \Exception
     */
    public static function init(Core &$core)
    {
        $container = Container::getInstance();
        /** @var RouteRegistry $registry */
        $registry = $container->get(RouteRegistry::class);
        $registry->setRoutables(
            (array) include Config::getFrameworkBaseRoute()
                . DIRECTORY_SEPARATOR
                . "config"
                . DIRECTORY_SEPARATOR
                . "controllers.registry.php"
        );
        /**
         * @Register the Default Error/Exception/ShutDown Scheduler as soon the Framework Core is instantiated.
         * Store those event handlers in the container,
         * in case you want to add more call back functions to any of the handler Schedulers.
         */
        $container->get(ErrorHandlerScheduler::class);
        $container->get(ExceptionHandlerScheduler::class);
        $container->get(ShutDownHandlerScheduler::class);
        $core->setHttpManager($container->get(HttpManager::class));
        $core->setConfig($container->get(Config::class));
        $core->setContainer($container);
        $core->getHttpManager()->boot();
        self::initiateSystemConfig($core);
    }

    /**
     * @param Core $core
     * @return bool
     */
    public static function initiateSystemConfig(Core $core): bool
    {
        $configs = $core->getConfig();
        $modulesConfig = $configs->appConfig;

        $iniSet = $modulesConfig["ini_set"];
        if (is_dir($iniSet["error_log"])) {
            $iniSet["error_log"] = Log::getLogFileName($modulesConfig["logStyle"], $iniSet["error_log"]);
        }
        foreach($iniSet as $key => $value) {
            ini_set($key, $value);
        }

        $allowedOrigins = $modulesConfig["allowed_origin"];
        $request = $core->getContainer()->get(Constants::REQUEST);
        $requestOrigin = $request->getServer()->get("HTTP_ORIGIN", "*");
        if (empty($allowedOrigins) || in_array($requestOrigin, $allowedOrigins)) {
            $request->setHeader("Access-Control-Allow-Origin", $requestOrigin);
            $request->setHeader("Access-Control-Allow-Methods", "GET, POST, PATCH, PUT, DELETE, OPTIONS");
            $request->setHeader("Access-Control-Allow-Headers", "Origin, Content-Type, X-Auth-Token, Authorization, Cache-Control, X-Requested-With");
        }                
        return true;
    }

    /**
     * @param Core $core
     * @return void
     * @throws \Exception
     */
    public static function boot(Core &$core): void
    {
        $routeMatch = $core->getHttpManager()->matchRequestRoutes();
        $container = $core->getContainer();
        if (!empty($routeMatch) && $routeMatch->isFound()) {
            $container->register(Constants::ROUTES, $routeMatch);
        }

        Connection::getInstance()->setActiveConnection(DbConnection::createDefaultConnection());

        $coreConfig = $core->getConfig();
        $appConfig = $coreConfig->appConfig->getArrayCopy();
        $injectables = (array) $appConfig["injectables"] ?? [];
        foreach($injectables as $name => $injectableClassOrVar) {
            $container->register($name, $container->create($injectableClassOrVar));
        }

        unset($appConfig["injectables"]);
        $container->register("CONFIG_VARS", $appConfig);

        $container->register(Constants::LANDING_PAGE, $core->getConfig()->getFrontScript());
        $core->setContainer($container);
    }

}