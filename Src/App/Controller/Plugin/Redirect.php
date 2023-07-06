<?php
namespace Emma\App\Controller\Plugin;

use Emma\Http\Response\ResponseInterface;

class Redirect extends ControllerPlugin
{
    /**
     * @param $routeString
     * @param array $urlParams
     * @param array $queryParams
     * @return ResponseInterface
     */
    public function toRoute($routeString, array $urlParams = array(), array $queryParams = array()): ResponseInterface
    {
        $url = $this->getController()->url($routeString, $urlParams);
        return $this->toUrl($url, $queryParams);
    }

    /**
     * @param $url
     * @param array $queryParams
     * @return ResponseInterface
     */
    public function toUrl($url, array $queryParams = array()): ResponseInterface
    {
        if (!empty($queryParams)) {
            return $this->getController()->getResponse()->setRedirect($url . "?" . http_build_query($queryParams));
        }
        return $this->getController()->getResponse()->setRedirect($url);
    }


}