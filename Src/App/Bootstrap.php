<?php
namespace Emma\App;

use Emma\Di\Container\Container;
use Emma\Common\Singleton\Singleton;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Bootstrap
{
    use Singleton;

    /**
     * @var Core
     */
    protected $FrameworkEngine;

    /**
     * Bootstrap constructor.
     */
    public function __construct()
    {
        $this->setFrameworkEngine(Core::getInstance()->start());
    }

    /**
     * @param string $service
     * @return bool|object
     */
    public function get($service)
    {
        if ($this->has($service)) {
            return $this->getContainer()->get($service);
        }
        return false;
    }

    /**
     * @param string $service
     * @return bool
     */
    public function has($service)
    {
        if ($this->getContainer()->has($service)) {
            return true;
        }
        if (class_exists($service)) {
            return true;
        }
        return false;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->getFrameworkEngine()->getContainer();
    }

    /**
     * @param $name
     * @return object
     */
    public function createInstance($name)
    {
        return $this->getContainer()->get($name);
    }

    /**
     * @return Core
     */
    public function getFrameworkEngine()
    {
        return $this->FrameworkEngine;
    }

    /**
     * @param Core $FrameworkEngine
     * @return Bootstrap
     */
    public function setFrameworkEngine($FrameworkEngine)
    {
        $this->FrameworkEngine = $FrameworkEngine;
        return $this;
    }

}