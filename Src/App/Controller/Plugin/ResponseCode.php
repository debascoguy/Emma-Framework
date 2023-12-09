<?php
namespace Emma\App\Controller\Plugin;
use Emma\Http\HttpStatus;
use Emma\Http\Response\ResponseInterface;

class ResponseCode extends ControllerPlugin
{
    /**
     * @param int $code
     * @param string|null $responseText
     * @return void
     * @throws \Exception
     */
    public function __invoke(int $code = HttpStatus::HTTP_OK, ?string $responseText = null): ResponseInterface
    {
        return $this->getController()->getResponse()->setResponseCode($code)->setResponseText($responseText);
    }
}