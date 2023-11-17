<?php

namespace Emma\App\ServiceManager\HttpInterceptors;
use Emma\Di\Container\ContainerManager;

trait HttpInterceptorManager
{
    use ContainerManager;

    public const INTERCEPTOR_REGISTRY = "INTERCEPTORS";

    public function addInterceptor(HttpInterceptorInterface $interceptor, string|int|null $key = null)
    {
        $interceptors = $this->getInterceptors();
        if (!empty($key) && !is_int($key)) {
            $interceptors[$key] = $interceptor;
        } else {
            $interceptors[] = $interceptor;
        }
        $this->getContainer()->register(self::INTERCEPTOR_REGISTRY, $interceptors);
    }

    public function removeInterceptor(HttpInterceptorInterface $interceptor)
    {
        $interceptors = $this->getInterceptors();
        $key = array_search($interceptor, $interceptors);
        if ($key !== false) {
            unset($interceptors[$key]);
        }
        $this->getContainer()->register(self::INTERCEPTOR_REGISTRY, $interceptors);
    }
    
    public function removeInterceptorByKey(string|int $key)
    {
        $interceptors = $this->getInterceptors();
        if (isset($interceptors[$key])) {
            unset($interceptors[$key]);
        }
        $this->getContainer()->register(self::INTERCEPTOR_REGISTRY, $interceptors);
    }

    public function hasInterceptor(string|int $key): bool 
    {
        $interceptors = $this->getInterceptors();
        return isset($interceptors[$key]);
    }

    public function getInterceptor(string|int $key): ?HttpInterceptorInterface
    {
        $interceptors = $this->getInterceptors();
        return $interceptors[$key] ?? null;
    }

    public function getInterceptors()
    {
        return $this->getContainer()->has(self::INTERCEPTOR_REGISTRY) ? $this->getContainer()->get(self::INTERCEPTOR_REGISTRY) : [];
    }
}
