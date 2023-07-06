<?php
namespace Emma\App\View\Service;
use Emma\App\View\View;
use Emma\Http\Response\Response;

/**
 * @Author: Ademola Aina
 * Date: 8/21/2017
 * Time: 7:11 AM
 */
class ViewHelper implements View
{
    /**
     * @var array|string
     */
    public array|string $data;

    /**
     * @var string
     */
    public string $routeString;

    /**
     * @var bool
     */
    public bool $json = false;

    /**
     * @var bool
     */
    public bool $rawOutput = false;


    /**
     * @param string $routeString
     * @param array $data
     * @return $this
     */
    public function __invoke(string $routeString, array $data = array())
    {
        $this->setRouteString($routeString)->setData($data);
        return $this;
    }

    /**
     * @return array|string
     */
    public function getData(): array|string
    {
        return $this->data;
    }

    /**
     * @param array|string $data
     * @return static
     */
    public function setData(array|string $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getRouteString(): string
    {
        return $this->routeString;
    }

    /**
     * @param string $routeString
     * @return ViewHelper
     */
    public function setRouteString(string $routeString): static
    {
        $this->routeString = $routeString;
        return $this;
    }

    /**
     * @return bool
     */
    public function isJson(): bool
    {
        return $this->json;
    }

    /**
     * @param bool $json
     * @return static
     */
    public function setJson(bool $json = true): static
    {
        $this->json = $json;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRawOutput(): bool
    {
        return $this->rawOutput;
    }

    /**
     * @param $rawOutput
     * @return static
     */
    public function setRawOutput($rawOutput): static
    {
        $this->rawOutput = $rawOutput;
        return $this;
    }

    /**
     * @param $result
     * @param Response $response
     * @return Response
     */
    public static function prepare($result, Response $response): Response
    {
        if ($result instanceof View)
        {
            if ($result->isJson()) {
                $response->setJson($result->getData());
            }
            else if ($result->isRawOutput()) {
                $response->setBody($result->getData());
            }
            else {
                $response->setBody(
                    (new ViewEngine())->render($result->getRouteString(), $result->getData())
                );
            }
        }
        return $response;
    }

}