<?php

namespace Emma\App\View\Service;

use Emma\App\Config;
use Emma\App\ServiceManager\PluginEngine;
use Emma\App\View\HelperPlugin\Css;
use Emma\App\View\HelperPlugin\Deprecated\BasePath;
use Emma\App\View\HelperPlugin\Deprecated\Url;
use Emma\App\View\HelperPlugin\IncludeFile;
use Emma\App\View\HelperPlugin\Js;
use Emma\App\View\HelperPlugin\Layout;
use Emma\App\View\HelperPlugin\PageHeader;
use Emma\App\View\HelperPlugin\PageTitle;
use Emma\Common\Utils\StringManagement;

/**
 * Class Template
 *
 * @method string escape($string)
 * @method string e($string)
 * @method Layout layout($name = '', $pageTitle = '')
 * @method IncludeFile include ($name, array $data = array())
 * @method Css css()
 * @method Js js()
 * @method PageHeader pageHeader($pageHeader = "")
 * @method PageTitle pageTitle()
 * @method BasePath basePath($path = '')
 * @method Url url($route = null, array $params = array())
 * @method \Emma\App\View\HelperPlugin\Template template($templateName = null)
 */
class Template {

    use PluginEngine;

    /**
     * @var ViewEngine
     */
    protected $viewEngine;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $folder;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var string
     */
    protected $layoutName;

    /**
     * @var string
     */
    protected $pageTitle = "";

    /**
     * @var string
     */
    protected $pageHeader = "";

    /**
     * @param ViewEngine $engine
     * @param string $name
     */
    public function __construct(ViewEngine $engine, $name) 
    {
        $this->viewEngine = $engine;
        $this->setName($name);
        $this->data = array_merge($this->data, $this->viewEngine->getData());
        $this->setSector("view");
    }

    /**
     * @param string $module
     * @param string $view
     * @return string
     */
    public function getViewPath(string $module = "", string $view = ""): string
    {
        if (empty($view)) {
            $view = $this->name;
        }
        $viewPath = Config::getFrameworkBaseRoute() . DIRECTORY_SEPARATOR;
        $viewPath .= "Modules" . DIRECTORY_SEPARATOR . StringManagement::underscoreToCamelCase($module, true);
        $viewPath .= DIRECTORY_SEPARATOR . "View" . DIRECTORY_SEPARATOR;
        $viewPath .= str_replace(":", DIRECTORY_SEPARATOR, $view) . '.php';
        return $viewPath;
    }

    /**
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function render(array $data = array()) {
        if (!$this->exists()) {
            throw new \BadMethodCallException(sprintf('Template "%s" could not be found.', $this->name));
        }

        $basePath = $this->basePath();
        $this->data = array_merge($this->data, $data, array("basePath" => $basePath));
        try {
            $moduleName = "";
            extract($this->data);
            ob_start();
            include $this->path($moduleName);
            $content = ob_get_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            throw new \BadMethodCallException(sprintf('View File "%s" could not be rendered', $this->name), 0, $e);
        }
        
        if (isset($this->layoutName)) {
            try {
                extract([
                    "content" => $content, 
                    "pageTitle" => $this->getPageTitle(), 
                    "pageHeader" => $this->getPageHeader(), 
                    "js" => $this->js()->render(), 
                    "css" => $this->css()->render() 
                ]);
                ob_start();
                include $this->template()->getViewTemplate($this->layoutName);
                $content = ob_get_clean();
            } catch (\Exception $e) {
                ob_end_clean();
                throw new \BadMethodCallException(sprintf('Template "%s" could not be rendered', $this->name), 0, $e);
            }
        }
        return str_replace("%basePath%", $basePath, $content);
    }

    /**
     * @param string $file
     * @return bool
     */
    public function exists($file = "") {
        $file = (empty($file)) ? $this->path() : $file;
        return file_exists($file);
    }

    /**
     * @param string $moduleName
     * @return string
     */
    public function path(&$moduleName = "") {
        /** Using Specified Folder and File Path */
        if (isset($this->file, $this->folder)) {
            $fullPath = trim($this->folder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->file;
            return $fullPath;
        }

        /** If Sending output through another module's view script. */
        if (StringManagement::contains($this->name, "::")) {
            $parts = explode('::', $this->name, 2);
            $moduleName = $parts[0];
            return $this->getViewPath($parts[0], $parts[1]);
        }

        /** Basic/Default Method */
        return $this->getViewPath();
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayoutName() {
        return $this->layoutName;
    }

    /**
     * @param string $layoutName
     * @return self
     */
    public function setLayoutName($layoutName) {
        $this->layoutName = $layoutName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageTitle() {
        return $this->pageTitle;
    }

    /**
     * @param string $pageTitle
     * @return Template
     */
    public function setPageTitle($pageTitle) {
        $this->pageTitle = $pageTitle;
        return $this;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return ViewEngine
     */
    public function getViewEngine() {
        return $this->viewEngine;
    }

    /**
     * @param ViewEngine $viewEngine
     * @return Template
     */
    public function setViewEngine($viewEngine) {
        $this->viewEngine = $viewEngine;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageHeader() {
        return $this->pageHeader;
    }

    /**
     * @param string $pageHeader
     * @return Template
     */
    public function setPageHeader($pageHeader) {
        $this->pageHeader = $pageHeader;
        return $this;
    }

}
