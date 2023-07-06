<?php
namespace Emma\App\ErrorHandler\Exception;

use Emma\Http\HttpStatus;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Exception501 extends BaseException
{
    /**
     * @var int
     */
    protected $code = HttpStatus::HTTP_NOT_IMPLEMENTED;

    /**
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", \Throwable $previous = null)
    {
        parent::__construct($message, HttpStatus::HTTP_NOT_IMPLEMENTED, $previous);
    }

}