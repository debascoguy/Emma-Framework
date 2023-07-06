<?php

namespace Emma\App\ErrorHandler;

use Emma\Common\Singleton\Singleton;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 9/19/2017
 * Time: 7:19 PM
 */
class ShutDownHandlerScheduler extends AbstractErrorHandler
{
    use Singleton;

    /**
     * ElementMvc_ErrorHandler_ShutdownScheduler
     */
    public function __construct()
    {
        parent::__construct(AbstractErrorHandler::REGISTER_SHUT_DOWN_FUNCTION, "invokeShutdownHandler");
        /** REGISTER THE DEFAULT SHUT DOWN HANDLER/FUNCTION */
        $this->registerEvent([$this, "defaultShutdownHandler"]);
    }

    /**
     * @return bool
     */
    public function invokeShutdownHandler(): bool
    {
        return $this->invokeRegisteredHandlerCallBacks();
    }

    /**
     * @return void
     */
    public function defaultShutdownHandler(): void
    {
        $E_FATAL = E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR;

        $error = error_get_last();
        if (!empty($error)) {
            $errno = $error['type'];
            $errstr = $error['message'];
            $errfile = $error['file'];
            $errline = $error['line'];
    
            if ($errno & $E_FATAL) {
                $message = '<b>' . $errno . ': </b>' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b><br/>';
                error_log(str_replace(array("<b>", "</b>", "<br/>"), "\r\n", $message));
    
                $serverName = $_SERVER["SCRIPT_NAME"] . "/error/exception/error500";
                header("Location: " . $serverName);
                header('Status: 500 Internal Server Error');
                exit();
            }
        }
    }
}