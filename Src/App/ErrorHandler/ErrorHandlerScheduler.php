<?php
namespace Emma\App\ErrorHandler;

use Emma\Common\Singleton\Singleton;
use Emma\Common\Utils\ArrayManagement;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 9/19/2017
 * Time: 6:49 PM
 */
class ErrorHandlerScheduler extends AbstractErrorHandler
{
    use Singleton;

    /**
     * ElementMvc_ErrorHandler_ErrorHandlerScheduler
     */
    public function __construct()
    {
        parent::__construct(AbstractErrorHandler::REGISTER_ERROR_HANDLER, "invokeErrorHandlers");
        /** REGISTER THE DEFAULT ERROR HANDLER/FUNCTION */
        $this->registerEvent([$this, "defaultErrorHandler"]);
    }

    /**
     * @param $errorCode
     * @param $errorMessage
     * @param $errorFile
     * @param $errorLine
     * @param array $errorContext
     * @return bool
     */
    public function invokeErrorHandlers($errorCode, $errorMessage, $errorFile, $errorLine, array $errorContext = []): bool
    {
        foreach ($this->getCallbacks() as $arguments) {
            $arguments->call(array($errorCode, $errorMessage, $errorFile, $errorLine, $errorContext));
        }
        return true;
    }

    /**
     * @param $errorCode
     * @param $errorMessage
     * @param $errorFile
     * @param $errorLine
     * @param array $errorContext
     * @return bool
     */
    public function defaultErrorHandler($errorCode, $errorMessage, $errorFile, $errorLine, array $errorContext = []): bool
    {
        if (!(error_reporting() & $errorCode)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return false;
        }
        $errorContext = ArrayManagement::array_flatten($errorContext);
        $message = "Error Report From Default System Error Handler \nError In File: " . $errorFile .
            "\nAt Line: " . $errorLine .
            "\nError Code: " . $errorCode .
            "\nError Message: " . $errorMessage ;

        error_log($message);
        $this->displayError($errorCode, $errorMessage, $errorFile, $errorLine, $errorContext);
        die("\nSystem Upgrade/Maintenance is in progress, please try again later. If Issue Persist, please contact: " . $this->getCustomerContactEmail());
    }
}