<?php
namespace Emma\App\Controller\Plugin;

use Emma\App\Config;
use Emma\App\Constants;
use Emma\App\ErrorHandler\Exception\BaseException;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 8/25/2017
 * Time: 6:55 AM
 */
class Template extends ControllerPlugin
{
    /**
     * @param $templateName
     * @return $this|mixed|string
     */
    public function __invoke($templateName)
    {
        if (empty($templateName)) {
            return $this;
        }
        return $this->getTemplateContent($templateName);
    }

    /**
     * @param $templateName
     * @return mixed|string
     * @throws BaseException
     */
    public function getTemplateContent($templateName)
    {
        $basePath = $this->getController()->url()->getBasePath();
        $html = file_get_contents($this->getTemplate($templateName));
        $html = str_replace("%basePath%", $basePath, $html);
        return $html;
    }

    /**
     * @return bool
     */
    public function includeTemplate($templateName)
    {
        include $this->getTemplate($templateName);
        return true;
    }

    /**
     * @return string path
     */
    public function getTemplate($templateName)
    {
        $templateConfig = $this->getTemplateConfig();
        return $templateConfig[$templateName];
    }

    /**
     * @return array
     */
    public function getTemplateConfig()
    {
        /** @var Config $config */
        $config = $this->getController()->getContainer()->get(Constants::CONFIG);
        return $config["templates"];
    }
}