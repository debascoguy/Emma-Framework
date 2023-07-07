<?php

namespace Emma\App;

use Emma\App\Connection\DbConnection;
use Emma\App\ErrorHandler\ErrorHandlerScheduler;
use Emma\App\ErrorHandler\Exception\Exception404;
use Emma\App\ErrorHandler\ExceptionHandlerScheduler;
use Emma\App\ErrorHandler\ShutDownHandlerScheduler;
use Emma\App\ServiceManager\MvcEventManager;
use Emma\Di\Container\Container;
use Emma\Http\HttpManager;
use Emma\Http\RouteRegistry;

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
        $core->setEventManager($container->get(MvcEventManager::class));
        $core->setContainer($container);
        self::initiateSystemConfig($core);
        $core->getHttpManager()->boot();
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
        if (is_dir($iniSet["error_log"])){
            $iniSet["error_log"] = Log::getLogFileName($modulesConfig["logStyle"], $iniSet["error_log"]);
        }
        foreach($iniSet as $key => $value) {
            ini_set($key, $value);
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
        } else {
            throw new Exception404("Route Not Found!");
        }

        DbConnection::getInstance()->connect([]);

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