<?php

namespace Emma\App\ServiceManager;

use Emma\App\Config;
use Emma\Common\Utils\StringManagement;
use Emma\Di\Container\Container;
use Emma\Http\Request\RequestFactory;

class Token
{
    /**
     * @return string
     */
    public static function generateCookie(): string
    {
        return sha1(StringManagement::generateRandomString());
    }

    /**
     * @return string
     */
    public static function getCsrfToken(): string
    {
        /** @var RequestFactory $request */
        $request = Container::getInstance()->get("Request");
        $config = Config::getInstance();
        $csrfParamName = $config->csrfParamName;
        if ($config->enableCsrfValidation && !$request->getCookies()->has($csrfParamName)){
            $token = self::generateCookie();
            $request->getCookies()->add($csrfParamName, $token);
        }
        return $request->getCookies()->get($csrfParamName, "");
    }

}