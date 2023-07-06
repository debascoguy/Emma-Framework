<?php
namespace Emma\App\ErrorHandler\Exception;

use Emma\Http\HttpStatus;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Exception404 extends BaseException
{
    /**
     * @var int Page Not Found Exception
     */
    protected $code = HttpStatus::HTTP_NOT_FOUND;

    /**
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", \Throwable $previous = null)
    {
        parent::__construct($message, HttpStatus::HTTP_NOT_FOUND, $previous);
    }

}