<?php

namespace Emma\App\ServiceManager;

use Emma\App\Controller\BaseController;
use Emma\App\ErrorHandler\Exception\BaseException;
use Emma\App\View\Service\ViewHelper;
use Emma\App\View\View;
use Emma\Common\CallBackHandler\CallBackHandler;
use Emma\Common\Singleton\Singleton;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class MvcEventManager
{
    use Singleton;

    /**
     * @var CallBackHandler[]|array
     */
    protected array $events = [];

    /**
     * @var bool
     */
    protected bool $terminateEvents = false;

    const PRE_CONTROLLER_EVENTS = "preControllerEvent";
    const POST_CONTROLLER_EVENTS = "postControllerEvent";
    const PRE_ACTION_EVENTS = "preActionEvent";
    const POST_ACTION_EVENTS = "postActionEvent";
    
    
    /**
     * @param string $key
     * @return array|CallBackHandler|CallBackHandler[]
     */
    public function getEvent($key = "")
    {
        if (!empty($key)) {
            return !empty($this->events[$key]) ? $this->events[$key] : null;
        }
        return [];
    }

    /**
     * @return array|CallBackHandler[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param array|CallBackHandler[] $events
     * @return MvcEventManager
     */
    public function setEvents($events)
    {
        $this->events = $events;
        return $this;
    }

     /**
     * @return bool
     * @throws BaseException
     *
     * Used To Trigger All Registered PRE-Action Events Before the actual Action Call...
     * Such as: Session Management Function that each controller may register as preAction Event
     *
     * Example: HOW TO USE
     * ====================
     *
     * $EventManager = $this->getContainer()->get("EventManager");
     * $EventManager->registerPreActionEvent(array($this, "sessionManagement"));
     *
     * @param $actualCallBack
     * @param array $argumentsOrMetadata
     * @param string $key
     * @return $this
     */
    public function registerEvent($actualCallBack, $argumentsOrMetadata = array(), $key = "")
    {
        if (empty($key)) {
            $this->events[] = new CallBackHandler($actualCallBack, $argumentsOrMetadata);
        } else {
            $this->events[$key][] = new CallBackHandler($actualCallBack, $argumentsOrMetadata);
        }
        return $this;
    }

    /**
     * @param $actualCallBack
     * @param array $argumentsOrMetadata
     */
    public function registerPreActionEvent($actualCallBack, $argumentsOrMetadata = array())
    {
        $this->registerEvent($actualCallBack, $argumentsOrMetadata, self::PRE_ACTION_EVENTS);
    }

    /**
     * @param $actualCallBack
     * @param array $argumentsOrMetadata
     */
    public function registerPostActionEvent($actualCallBack, $argumentsOrMetadata = array())
    {
        $this->registerEvent($actualCallBack, $argumentsOrMetadata, self::POST_ACTION_EVENTS);
    }

    /**
     * @return array|CallBackHandler|CallBackHandler[]
     */
    public function getPreActionEvent()
    {
        return $this->getEvent(self::PRE_ACTION_EVENTS);
    }

    /**
     * @return array|CallBackHandler|CallBackHandler[]
     */
    public function getPostActionEvent()
    {
        return $this->getEvent(self::POST_ACTION_EVENTS);
    }

    /**
     * @return array|CallBackHandler|CallBackHandler[]
     */
    public function getPreControllerEvent()
    {
        return $this->getEvent(self::PRE_CONTROLLER_EVENTS);
    }

    /**
     * @return array|CallBackHandler|CallBackHandler[]
     */
    public function getPostControllerEvent()
    {
        return $this->getEvent(self::POST_CONTROLLER_EVENTS);
    }

    /**
     * @param BaseController $controller
     * @param $state
     * @throws \Exception
     */
    public function handleEvents($state, $controller)
    {
        if ($this->isTerminateEvents()) {
            return $controller->getResponse();
        }

        $registry = $this->getEvent($state);
        if (!empty($registry) && is_array($registry)) {
            foreach ($registry as $callable) {
                $result = $callable->call($callable->getMetadata());
                if ($this->isTerminateEvents() || $result instanceof View) {
                    return ViewHelper::prepare($result, $controller->getResponse());
                }
            }
        }        
        return null;
    }

    /**
     * @return bool
     */ 
    public function isTerminateEvents()
    {
        return $this->terminateEvents;
    }

    /**
     * @return  self
     */ 
    public function setTerminateEvents(bool $terminateEvents)
    {
        $this->terminateEvents = $terminateEvents;
        return $this;
    }

    /**
     * @return self
     */
    public function stop()
    {
        return $this->setTerminateEvents(true);
    }
}