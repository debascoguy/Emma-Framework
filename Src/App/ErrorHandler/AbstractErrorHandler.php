<?php

namespace Emma\App\ErrorHandler;

use Emma\Common\CallBackHandler\CallBackHandler;
use Emma\Http\HttpStatus;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
abstract class AbstractErrorHandler implements EventHandlerInterface
{
    /**
     * @var CallBackHandler[]
     */
    private array $callbacks = []; // array to store user callbacks

    private mixed $handlerType;

    const REGISTER_SHUT_DOWN_FUNCTION = 0;
    const REGISTER_ERROR_HANDLER = 1;
    const REGISTER_EXCEPTION_HANDLER = 2;

    protected string $customerContactEmail = "";

    /**
     * @param int $handlerType
     * @param string $registryFunction
     */
    public function __construct(int $handlerType = self::REGISTER_ERROR_HANDLER, string $registryFunction = "invokeRegisteredHandlerCallBacks")
    {
        $this->callbacks = array();

        switch ($handlerType) {
            case self::REGISTER_SHUT_DOWN_FUNCTION  :
                register_shutdown_function(array($this, $registryFunction));
                break;

            case self::REGISTER_EXCEPTION_HANDLER   :
                set_exception_handler(array($this, $registryFunction));
                break;

            default :
                set_error_handler(array($this, $registryFunction));
                break;
        }
        $this->setCustomerContactEmail("admin@" . ltrim($_SERVER["SERVER_NAME"], "www."));
    }

    /**
     * @return CallBackHandler[]
     */
    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    /**
     * @param array $callbacks
     * @return $this
     */
    public function setCallbacks(array $callbacks): static
    {
        $this->callbacks = $callbacks;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHandlerType(): mixed
    {
        return $this->handlerType;
    }

    /**
     * @param mixed $handlerType
     * @return $this
     */
    public function setHandlerType(mixed $handlerType): static
    {
        $this->handlerType = $handlerType;
        return $this;
    }

    /**
     * @return true
     * pass in an argument that consist of ($callable, $_args...)
     */
    protected function registerEvent(): true
    {
        $callback = func_get_args();

        if (empty($callback)) {
            trigger_error('No callback passed to ' . __FUNCTION__ . ' method', E_USER_ERROR);
        }
        if (!is_callable($callback[0])) {
            trigger_error('Invalid callback passed to the ' . __FUNCTION__ . ' method', E_USER_ERROR);
        }
        $actualCallBack = array_shift($callback);
        $argumentsOrMetadata = $callback;
        $this->callbacks[] = new CallBackHandler($actualCallBack, $argumentsOrMetadata);
        return true;
    }

    /**
     * @return bool
     */
    public function invokeRegisteredHandlerCallBacks(): bool
    {
        foreach ($this->callbacks as $arguments) {
            $arguments->call($arguments->getMetadata());
        }
        return true;
    }

    /**
     * @param $errorCode
     * @param $errorMessage
     * @param $errorFile
     * @param $errorLine
     * @param array $errorContext
     */
    public function displayError($errorCode, $errorMessage, $errorFile, $errorLine, array $errorContext = [])
    {
        echo "<div style=' margin: auto; width: 60%; padding: 10px; '>";

        if($errorCode == HttpStatus::HTTP_UNAUTHORIZED)
        {
            echo "<h3>
                <span style='color:red;'>Un-Authorized Request: $errorMessage</span><br>";
            echo "<span style='color:red;'>Code:</span> $errorCode<br>";
            echo "</h3>";
        }
        else
        {
            echo "<h3>
                <span style='color:red; text-decoration: underline;'>$errorMessage</span><br>";
            echo "<span style='color:red;'>Code:</span> $errorCode<br>";
            echo "<span style='color:red;'>File:</span> $errorFile ==>> <span style='color:red;'>On Line:</span> $errorLine";
            if(!empty($errorContext)){
                echo "<span style='color:red;'>Context:</span> " . implode(" ", $errorContext);
            }
            echo "</h3>";

            echo "<div style='font-size: 16px; line-height: 2px; border: 3px solid #f2f246; padding: 25px; background-color: #d9d979; '>";

            if (@\file_exists($errorFile) && $handle = fopen($errorFile, "r")) {
                $line_number = 1;
                while (($lineString = fgets($handle, 4096)) !== false) {
                    $lineString = htmlspecialchars($lineString);
                    $lineString = str_replace([" ", "\n"], ["&nbsp;&nbsp;", "<br/>"], $lineString);
                    /** Highlight Text/Keyword if found */
                    if ($line_number == $errorLine){
                        $lineString = sprintf("<p><b><span style='background-color:%s'>%s</span></b></p>", "red", $lineString);
                    }
                    else{
                        $lineString = "<p>".$lineString."</p>";
                    }
                    echo $lineString;
                    $line_number++;
                }
                fclose($handle);
            }
            echo "</div>";
        }

        echo "</div>";
    }

    public function displayException(\Exception $exception)
    {
        $file = $exception->getFile();
        $code = $exception->getCode();
        $message = $exception->getMessage();
        $line = $exception->getLine();
        $this->displayError($code, $message, $file, $line);
    }

    /**
     * @return string
     */
    public function getCustomerContactEmail(): string
    {
        return $this->customerContactEmail;
    }

    /**
     * @param mixed $customerContactEmail
     * @return AbstractErrorHandler
     */
    public function setCustomerContactEmail(string $customerContactEmail): static
    {
        $this->customerContactEmail = $customerContactEmail;
        return $this;
    }

}