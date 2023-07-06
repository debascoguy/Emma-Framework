<?php
namespace Emma\App\View\HelperPlugin;

use Emma\App\Config;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 8/25/2017
 * Time: 6:55 AM
 *
 */
class Template extends BaseViewHelper
{
    /**
     * @param $templateName
     * @return $this|mixed|string
     */
    public function __invoke($templateName = null)
    {
        if (empty($templateName)) {
            return $this;
        }
        return $this->getViewTemplateContent($templateName);
    }

    /**
     * @param $templateName
     * @return string
     */
    public function getViewTemplateContent($templateName): string
    {
        $basePath = $this->getTemplate()->url()->getBasePath();
        $html = file_get_contents($this->getViewTemplate($templateName));
        return str_replace("%basePath%", $basePath, $html);
    }

    /**
     * @param $layoutName
     * @return string
     */
    public function getViewTemplate($layoutName): string
    {
        $config = Config::getInstance();
        $templateConfig = $config->appConfig->offsetGet("templates");
        return $templateConfig[$layoutName] ?? "";
    }

}