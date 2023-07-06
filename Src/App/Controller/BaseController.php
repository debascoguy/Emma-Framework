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
use Emma\App\ServiceManager\MvcEventManager;
use Emma\App\ServiceManager\PluginEngine;
use Emma\Di\Container\ContainerManager;
use Emma\Http\HttpStatus;
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
 * @method ResponseInterface json(array $params = array(), int $status = 200)
 * @method ResponseInterface restResponse($data, $status = 200)
 * @method Template template($templateName)
 * @method CurlManager|\Emma\App\ServiceManager\CurlManager curl($url, $postData = array())
 */
abstract class BaseController {

    use ContainerManager, PluginEngine;

    /**
     * @var Request
     */
    protected RequestInterface $request;

    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    /**
     * @var MvcEventManager|null
     */
    protected ?MvcEventManager $mvcEventManager = null;


    public function __construct() 
    {
        $this->setSector("controller");
        $this->setRequest($this->getContainer()->get(Constants::REQUEST));
        $this->setResponse($this->getContainer()->get(Constants::RESPONSE));
        $this->setMvcEventManager($this->getContainer()->get(Constants::EVENT_MANAGER));
        $this->getMvcEventManager()->registerPreActionEvent([$this, "authorization"]);
    }

    /**
     * @return bool
     */
    public function authorization(): bool
    {
        /**
         * If REQUEST is Authorized; => set response code(200) and return [json|view|true] --> (optional)
         * Otherwise, invoke $this->getEventManager()->stop() and set response code(405) and return [json|view|false].
         */
        $this->responseCode(HttpStatus::HTTP_OK);
        return true;
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

    /**
     * @return  MvcEventManager
     */ 
    public function getMvcEventManager(): MvcEventManager
    {
        return $this->mvcEventManager;
    }

    /**
     * @param MvcEventManager  $mvcEventManager
     * @return $this
     */ 
    public function setMvcEventManager(MvcEventManager $mvcEventManager): static
    {
        $this->mvcEventManager = $mvcEventManager;
        return $this;
    }
}
