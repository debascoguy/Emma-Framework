<?php

namespace Emma\App\ErrorHandler\Exception;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 8/19/2017
 * Time: 1:26 PM
 */
class BaseException extends \Exception
{
    /**
     * ElementMvc_ErrorHandler_Exception_BaseException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return get_class($this) . ": [{$this->code}]: {$this->message}\n";
    }
}