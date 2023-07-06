<?php
namespace Emma\App\Controller\Plugin;

use Emma\Http\Request\RequestInterface;

class RequestPlugin extends ControllerPlugin
{
    /**
     * @return RequestInterface
     */
    public function __invoke(): RequestInterface
    {
        return $this->getController()->getRequest();
    }

}
