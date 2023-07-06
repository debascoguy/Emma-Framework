<?php

namespace Emma\App\ServiceManager;

use Emma\App\Constants;
use Emma\App\Controller\Plugin\ControllerPlugin;
use Emma\App\View\HelperPlugin\BaseViewHelper;
use Emma\Common\CallBackHandler\CallBackHandler;
use Emma\Di\Container\ContainerManager;

/**
 * Class PluginEngine
 * @package Emma\ServiceManager
 */
trait PluginEngine
{
    use ContainerManager;

    protected $plugins = [];

    protected string $sector = "";
    
    /**
     * @param $methodName
     * @param $arguments
     * @return mixed|Object
     * @throws \Exception
     */
    public function __call($methodName, $arguments) 
    {
        if (empty($this->plugins)) {
            $configs = $this->getContainer()->get(Constants::CONFIG);
            $this->plugins = $configs->appConfig["plugins"][$this->sector];
        }

        return $this->get($methodName, $arguments);
    }

    /**
     * @param string $name
     * @param array $options
     * @return object
     * @throws \Exception
     */
    public function get($name, array $options = null)
    {
        if (!$this->has($name)) {
            throw new \BadMethodCallException(sprintf('Plugin "%s" not found.', $name));
        }

        $plugin = $this->plugins[$name];
        if (is_object($plugin)) {
            return $plugin;
        }

        if ($this->container->has($name)) {
            $object = $this->container->get($name, $options);
            $this->validatePlugin($object);
            $this->register($name, $object);
            return $object;
        }

        if (class_exists($plugin)) {
            $object = $this->container->get($plugin, $options);
            $this->validatePlugin($object);
            if (is_callable($object)) {
                $objectState = CallBackHandler::get($object, $options, true);
            } elseif (is_callable([$plugin, '__invoke'])) {
                //TODO: remove if php version is up-to  5.3+
                $objectState = CallBackHandler::get([$object, '__invoke'], $options, true);
            }
            $object = $objectState ?? $object;
            $this->register($name, $object);
            $this->container->register($name, $object);
            return $object;
        }

        $this->validatePlugin($plugin);
        $this->register($name, $plugin);

        return $plugin;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->plugins[$name]) || $this->container->has($name);
    }

    /**
     * @param $plugin
     * @return bool
     * @throws \Exception
     */
    public function validatePlugin(&$plugin)
    {
        if (!is_object($plugin)) {
            throw new \Exception(sprintf('Plugin of type %s is invalid', (is_object($plugin) ? get_class($plugin) : gettype($plugin))));
        }

        if ($this->sector == "controller") {
            if (!method_exists($plugin, 'setController')) {
                return;
            }
            if (!$plugin instanceof ControllerPlugin) {
                throw new \BadFunctionCallException(sprintf('Plugin of type %s is invalid', (is_object($plugin) ? get_class($plugin) : gettype($plugin))));
            }
            $plugin->setController($this);
        }

        if ($this->sector == "view") {
            if (!method_exists($plugin, 'setViewEngine')) {
                return;
            }
            if (!$plugin instanceof BaseViewHelper) {
                throw new \BadFunctionCallException(sprintf('Plugin of type %s is invalid', (is_object($plugin) ? get_class($plugin) : gettype($plugin))));
            }
            $plugin->setTemplate($this);
            $plugin->setViewEngine($this->viewEngine);
        }

        return true;
    }

    /**
     * @param string $name
     * @param string $helper
     * @return self
     * @throws \Exception
     */
    public function register($name, $helper)
    {
        $this->plugins[$name] = $helper;
        return $this;
    }


    /**
     * @return string
     */ 
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * @return self
     */ 
    public function setSector(string $sector)
    {
        $this->sector = $sector;
        return $this;
    }
}
