<?php
namespace Emma\App\Controller\Plugin;
use Emma\App\View\Service\ViewHelper;

class Render extends ControllerPlugin
{
    /**
     * @param $routeString
     * @param array $data
     * @return ViewHelper
     */
    public function __invoke($routeString, $data = array())
    {
        $ViewModel = new ViewHelper();
        $ViewModel->setData($data)->setRouteString($routeString);
        return $ViewModel;
    }

}