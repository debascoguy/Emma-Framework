<?php
namespace Emma\Console\Core;

use Emma\App\ErrorHandler\Exception\BaseException;
use Emma\App\ErrorHandler\Exception\Exception404;
use Emma\App\ErrorHandler\Exception\Exception501;
use Emma\Console\ConsoleInterface;
use Emma\Di\Autowire\Autowire;
use Emma\Di\Autowire\AutowireFactory;
use Emma\Di\Container\Container as DiContainer;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Container extends DiContainer
{

    /**
     *  Container
     */
    public function __construct()
    {
        parent::__construct();
        $factory = new AutowireFactory();
        $factory->setContainer($this);
        $factory->make(Autowire::class);
        $registry = (array)include_once dirname(__DIR__) . "/Registry.php";
        foreach ($registry as $name => $callable) {
            $parameterOrMethod = [];
            $job = $factory->autowire($callable, $parameterOrMethod);
            if ($job instanceof ConsoleInterface) {
                $this->register($name, $job);
            }
        }
    }

    /**
     * @param $name
     * @param $default
     * @return ConsoleInterface
     * @throws BaseException
     */
    public function get($name, $default=null)
    {
        if ($name == Autowire::class) {
            return parent::get(Autowire::class);
        }

        if ($this->has($name)) {
            $containerBag = $this->getContainer();
            if (!$containerBag[$name] instanceof ConsoleInterface) {
                throw new Exception501(
                    "Console Job MUST implement ConsoleInterface"
                );
            }
            return $containerBag[$name];
        }
        throw new Exception404("Console Job Not Found!");
    }


}