<?php

// https://www.php.net/manual/en/timezones.america.php
//https://www.php.net/manual/en/function.date-default-timezone-set.php
date_default_timezone_set('America/Chicago');

include __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR . "autoload.php";

try {
    $response = (new \Emma\Emma())->start()->handle();
} catch (\Emma\App\ErrorHandler\Exception\BaseException|ReflectionException $e) {
    die($e->getMessage());
}
$response->renderResponse();