<?php

namespace Emma\App\Controller;

use Emma\App\Constants;
use Emma\App\Controller\Plugin\CurlManager;
use Emma\App\Controller\Plugin\Output;
use Emma\App\Controller\Plugin\Redirect;
use Emma\App\Controller\Plugin\Render;
use Emma\App\Controller\Plugin\ResponseCode;
use Emma\App\Controller\Plugin\Template;
use Emma\App\Controller\Plugin\Url;
use Emma\App\ServiceManager\HttpInterceptors\AuthorizationInterceptorService;
use Emma\App\ServiceManager\HttpInterceptors\HttpInterceptorManager;
use Emma\App\ServiceManager\PluginEngine;
use Emma\Di\Container\ContainerManager;
use Emma\Http\Request\Request;
use Emma\Http\Request\RequestInterface;
use Emma\Http\Response\ResponseInterface;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 *
 * @method mixed|Request _request()
 * @method string|Url url($route = null, array $params = array())
 * @method Redirect redirect()
 * @method Render render($name, array $params = array())
 * @method Output output($html)
 * @method ResponseCode responseCode($code = 200, $responseText = null)
 * @method ResponseInterface json(array $params = array(), int $httpStatus = 200)
 * @method ResponseInterface restResponse(string $status, array|string|null $data, ?string $message = null, int $httpStatus = 200)
 * @method Template template($templateName)
 * @method CurlManager|\Emma\App\ServiceManager\CurlManager curl($url, $postData = array())
 */
abstract class BaseController {

    use ContainerManager, PluginEngine, HttpInterceptorManager;

    /**
     * @var Request
     */
    protected RequestInterface $request;

    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $response;


    public function __construct() 
    {
        $this->setRequest($this->getContainer()->get(Constants::REQUEST));
        $this->setResponse($this->getContainer()->get(Constants::RESPONSE));
        $this->addInterceptor($this->getContainer()->get(AuthorizationInterceptorService::class));
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     * @return BaseController
     */
    public function setRequest(RequestInterface $request): static
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     * @return BaseController
     */
    public function setResponse(ResponseInterface $response): static
    {
        $this->response = $response;
        return $this;
    }
}
