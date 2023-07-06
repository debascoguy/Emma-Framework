<?php
namespace Emma\App\Controller;


interface PluginInterface
{
    /**
     * @return BaseController
     */
    public function getController();

    /**
     * @param BaseController $controller
     * @return self
     */
    public function setController(BaseController $controller);
}
