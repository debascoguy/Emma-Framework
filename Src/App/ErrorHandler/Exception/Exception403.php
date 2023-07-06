<?php
namespace Emma\App\ErrorHandler\Exception;

use Emma\Http\HttpStatus;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Exception403 extends BaseException
{
    /**
     * @var int Page Not Found Exception
     */
    protected $code = HttpStatus::HTTP_FORBIDDEN;

    /**
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", \Throwable $previous = null)
    {
        parent::__construct($message, HttpStatus::HTTP_FORBIDDEN, $previous);
    }

}