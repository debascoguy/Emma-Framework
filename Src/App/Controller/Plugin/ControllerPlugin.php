<?php

namespace Emma\App\Controller\Plugin;

use Emma\App\Controller\BaseController;
use Emma\App\Controller\PluginInterface;

class ControllerPlugin implements PluginInterface
{
    /**
     * @var BaseController
     */
    protected $controller;

    /**
     * @return BaseController
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param BaseController $controller
     * @return self
     */
    public function setController(BaseController $controller)
    {
        $this->controller = $controller;
        return $this;
    }
}
