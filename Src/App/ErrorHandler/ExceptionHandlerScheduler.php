<?php

namespace Emma\App\ErrorHandler;

use Emma\App\Constants;
use Emma\Common\Singleton\Singleton;
use Emma\Di\Container\Container;
use Emma\Http\Router\Route\Route;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 9/19/2017
 * Time: 7:11 PM
 */
class ExceptionHandlerScheduler extends AbstractErrorHandler
{
    use Singleton;

    /**
     * ElementMvc_ErrorHandler_ExceptionHandlerScheduler
     */
    public function __construct()
    {
        parent::__construct(AbstractErrorHandler::REGISTER_EXCEPTION_HANDLER, "callExceptionHandler");
        /** REGISTER THE DEFAULT EXCEPTION HANDLER/FUNCTION */
        $this->registerEvent(array($this, "defaultExceptionHandler"));
    }

    /**
     * @param $exception
     * @return bool
     */
    public function callExceptionHandler($exception): bool
    {
        foreach ($this->getCallbacks() as $arguments) {
            $arguments->call(array($exception));
        }
        return true;
    }

    /**
     * @param $exception
     * @return void
     */
    public function defaultExceptionHandler($exception): void
    {
        if ($exception instanceof \Throwable) {
            $container = Container::getInstance();
            /** @var Route $routes */
            $routes = $container->get(Constants::ROUTES);
            $callable = $routes->getCallable();
            if (@method_exists($callable[0], $callable[1])) {
                $message = "Error Report From Default System Exception Handler \nError In File: " . $exception->getFile()
                    . "\nAt Line: " . $exception->getLine() . "\nError Message: " . $exception->getMessage();
                error_log($message);
                error_log("Callable: " . json_encode($callable));
                error_log("\nTRACE INFORMATION BELOW:");
                $backtrace = debug_backtrace();
                unset($backtrace[0]);
                $msg = [];
                foreach ($backtrace as $error) {
                    $msg[] = "<b>Error in file:</b> {$error['file']}  <b>[Line #:{$error['line']}]</b>\n"
                        . "<b>Function:</b> {$error['function']}";
                }
                error_log(implode("\n", $msg));
            }
        }
        else {
            ob_start();
            var_dump($exception);
            $err = ob_get_clean();
            error_log($err);
            die($err);
        }
    }

}