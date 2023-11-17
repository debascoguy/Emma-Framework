<?php
namespace Emma\App;

use Emma\App\ServiceManager\HttpInterceptors\HttpInterceptorInterface;
use Emma\App\ServiceManager\HttpInterceptors\HttpInterceptorManager;
use Emma\Common\CallBackHandler\CallBackHandler;
use Emma\Common\Singleton\Interfaces\SingletonInterface;
use Emma\Di\Autowire\Autowire;
use Emma\Di\Autowire\Interfaces\AutowireInterface;
use Emma\Di\Container\ContainerManager;
use Emma\Http\HttpManager;
use Emma\Http\HttpStatus;
use Emma\Http\Request\RequestInterface;
use Emma\Http\Response\ResponseInterface;
use Emma\Http\Router\Interfaces\RouteInterface;
use Emma\Http\Router\Route\Route;


class Core implements SingletonInterface
{
    use ContainerManager, HttpInterceptorManager;

    /**
     * @var Config|null
     */
    protected ?Config $config = null;

    /**
     * @var HttpManager
     */
    protected HttpManager $httpManager;

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
        $route = $this->getContainer()->has(Constants::ROUTES) ? $this->getContainer()->get(Constants::ROUTES) : null;
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

        /** @var HttpInterceptorInterface $interceptor */
        $interceptors = $this->getInterceptors();
        foreach($interceptors as $interceptor) {
            $response = $interceptor->intercept($request, $response);
            if ($response instanceof ResponseInterface && 
                ($response->isRedirect() || $response->getResponseCode() !== HttpStatus::HTTP_OK)) {
                return $response;
            }
        }

        $response = CallBackHandler::get($callable, $diParams);
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
}