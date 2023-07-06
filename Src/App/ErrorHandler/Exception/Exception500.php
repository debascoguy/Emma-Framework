<?php
namespace Emma\App\ErrorHandler\Exception;

use Emma\Http\HttpStatus;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Exception500 extends BaseException
{
    /**
     * @var int
     */
    protected $code = HttpStatus::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", \Throwable $previous = null)
    {
        parent::__construct($message, HttpStatus::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }
}