<?php

namespace Emma\App\ServiceManager\HttpInterceptors;

use Emma\Http\HttpStatus;
use Emma\Http\Request\RequestInterface;
use Emma\Http\Response\ResponseInterface;

class AuthorizationInterceptorService implements HttpInterceptorInterface
{
    /**
     * You can inject services for logical/business operations.
     */

    public function intercept(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /**
         * If REQUEST is Authorized; => set response code(200) and return [json|view|true] --> (optional)
         * Otherwise, update the response code accordingly and return response
         */
        $response->setResponseCode(HttpStatus::HTTP_OK);
        return $response;
    }
}
