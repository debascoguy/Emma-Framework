<?php
namespace Emma\App\Controller\Plugin;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 8/21/2017
 * Time: 9:27 PM
 */
class Url extends ControllerPlugin
{
    /**
     * @param null $routeString
     * @param array|null $params
     * @return $this|string
     */
    public function __invoke($routeString = null, array $params = array())
    {
        if ($routeString === null) {
            return $this;
        }
        return $this->fromRoute($routeString, $params);
    }

    /**
     * @param $routeString
     * @param $params
     * @return string
     */
    public function fromRoute($routeString, $params)
    {
        $router = RouteBuilder::getInstance();
        return $router->assemble($routeString, $params);
    }

    /**
     * @param $routeString
     * @param $params
     * @return string
     */
    public function getUrl($routeString, $params)
    {
        return $this->getHomeURL($this->fromRoute($routeString, $params));
    }

    /**
     * @param string $append
     * @return string
     */
    public function getHomeURL($append = "")
    {
        $pageURL = ($_SERVER["HTTPS"] == "on") ? 'https://' : 'http://';
        $pageURL .= ($_SERVER["SERVER_PORT"] != "80") ?
            $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] : $_SERVER["SERVER_NAME"];
        return $pageURL . $append;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        $basePath = $this->fromRoute("", array());
        if (substr($basePath, -4) === '.php') {
            $basePath = dirname($basePath);
        }
        if ($basePath == "/") {
            $basePath = "";
        }
        return $basePath;
    }

}