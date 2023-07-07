<?php
namespace Emma\App\ServiceManager;

use Emma\Di\Attribute\Inject;
use Emma\Di\Container\ContainerManager;
use Emma\Http\Request\RequestFactory;
use Emma\Http\Request\RequestInterface;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
abstract class AbstractService
{
    use ContainerManager;

    /**
     * @var RequestInterface
     */
    #[Inject(name: RequestFactory::class)]
    protected ?RequestInterface $request = null;

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     * @return $this
     */
    public function setRequest(RequestInterface $request): static
    {
        $this->request = $request;
        return $this;
    }

}