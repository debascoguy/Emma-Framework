<?php

namespace Emma\App\ErrorHandler\Exception;

use Emma\Http\HttpStatus;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Exception401 extends BaseException
{
    /**
     * @var int
     */
    protected $code = HttpStatus::HTTP_UNAUTHORIZED;

    /**
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", \Throwable $previous = null)
    {
        parent::__construct($message, HttpStatus::HTTP_UNAUTHORIZED, $previous);
    }

}