<?php
namespace Emma\App\Controller\Plugin;
use Emma\Http\HttpStatus;

class ResponseCode extends ControllerPlugin
{
    /**
     * @param int $code
     * @param string|null $responseText
     * @return void
     * @throws \Exception
     */
    public function __invoke(int $code = HttpStatus::HTTP_OK, ?string $responseText = null)
    {
        $this->getController()->getResponse()->setResponseCode($code)->setResponseText($responseText);
    }
}