<?php
namespace Emma\App;

use Emma\App\ServiceManager\MvcEventManager;
use Emma\Common\CallBackHandler\CallBackHandler;
use Emma\Common\Singleton\Interfaces\SingletonInterface;
use Emma\Di\Autowire\Autowire;
use Emma\Di\Autowire\Interfaces\AutowireInterface;
use Emma\Di\Container\ContainerManager;
use Emma\Http\HttpManager;
use Emma\Http\Request\RequestInterface;
use Emma\Http\Response\ResponseInterface;
use Emma\Http\Router\Interfaces\RouteInterface;
use Emma\Http\Router\Route\Route;

/**
 * Class Core
 */
class Core implements SingletonInterface
{
    use ContainerManager;

    /**
     * @var Config|null
     */
    protected ?Config $config = null;

    /**
     * @var HttpManager
     */
    protected HttpManager $httpManager;

    /**
     * @var MvcEventManager|null
     */
    protected ?MvcEventManager $eventManager = null;

    /**
     * @var string
     */
    public string $frontScript = "";

    /**
     * @var Core|null
     */
    private static ?Core $instance = null;

    /**
     * @var bool
     */
    protected bool $isBooted = false;

    /**
     * @param string|null $frontScript
     * @return static
     * @throws \Exception
     */
    public static function getInstance(?string $frontScript = ""): self
    {
        if (self::$instance == null) {
            self::$instance = new self($frontScript);
            self::$instance->start();
        }
        return self::$instance;
    }

    /**
     * @param string $frontScript
     * @throws \Exception
     */
    public function __construct(string $frontScript = "")
    {
        Boot::init($this);
        $this->getConfig()->setFrontScript($frontScript);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function start(): self
    {
        if ($this->isBooted) {
            return $this;
        }
        Boot::boot($this);
        self::$instance = $this;
        $this->isBooted = true;
        return $this;
    }

    /**
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(): ResponseInterface
    {
        $route = $this->getContainer()->get(Constants::ROUTES);
        /** @var ResponseInterface $response */
        $response = $this->getContainer()->get(Constants::RESPONSE);
        return ($route instanceof Route) ? $this->handleMiddleware($route) : $response;
    }

    /**
     * @param RouteInterface $route
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handleMiddleware(RouteInterface $route): ResponseInterface
    {
        /** @var RequestInterface $request */
        $request = $this->getContainer()->get(Constants::REQUEST);
        /** @var ResponseInterface $response */
        $response = $this->getContainer()->get(Constants::RESPONSE);
        $request->setParams($route->getParams());

        if ($request->getServer()->isOptions()) {
            //Check if request is a test 'Preflight' request
            return $response;
        }

        /** @var AutowireInterface $autowire */
        $autowire = $this->getContainer()->get(Autowire::class);

        $diParams = $route->getParams();
        $callable = $autowire->autowire($route->getCallable(), $diParams);

        $response = $this->getEventManager()->handleEvents(MvcEventManager::POST_CONTROLLER_EVENTS, $callable[0]);
        if ($response instanceof ResponseInterface) {
            return $response;
        }

        $response = $this->getEventManager()->handleEvents(MvcEventManager::PRE_ACTION_EVENTS, $callable[0]);
        if ($response instanceof ResponseInterface) {
            return $response;
        }

        $response = CallBackHandler::get($callable, $diParams);
        if ($response instanceof ResponseInterface && $response->isRedirect()) {
            return $response;
        }

        $response1 = $this->getEventManager()->handleEvents(MvcEventManager::POST_ACTION_EVENTS, $callable[0]);
        if ($response1 instanceof ResponseInterface) {
            return $response1;
        }
        return $response;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param Config $config
     * @return Core
     */
    public function setConfig(Config $config): static
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return HttpManager
     */
    public function getHttpManager(): HttpManager
    {
        return $this->httpManager;
    }

    /**
     * @param HttpManager $httpManager
     * @return Core
     */
    public function setHttpManager(HttpManager $httpManager): Core
    {
        $this->httpManager = $httpManager;
        return $this;
    }

    /**
     * @return MvcEventManager
     */
    public function getEventManager(): MvcEventManager
    {
        return $this->eventManager;
    }

    /**
     * @param MvcEventManager $eventManager
     * @return Core
     */
    public function setEventManager(MvcEventManager $eventManager): static
    {
        $this->eventManager = $eventManager;
        return $this;
    }
}