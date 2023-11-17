<?php

namespace Emma\App\ServiceManager\HttpInterceptors;

use Emma\Http\Request\RequestInterface;
use Emma\Http\Response\ResponseInterface;

interface HttpInterceptorInterface
{

    public function intercept(RequestInterface $request, ResponseInterface $response): ?ResponseInterface;
}
