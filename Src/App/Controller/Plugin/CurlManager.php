<?php
namespace Emma\App\Controller\Plugin;


class CurlManager extends ControllerPlugin
{
    /**
     * @param $url
     * @param array $postData
     * @return \Emma\App\ServiceManager\CurlManager
     */
    public function __invoke($url, array $postData = array()): \Emma\App\ServiceManager\CurlManager
    {
        return new \Emma\App\ServiceManager\CurlManager($url, $postData);
    }


}