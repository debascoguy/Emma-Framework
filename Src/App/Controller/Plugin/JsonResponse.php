<?php
namespace Emma\App\Controller\Plugin;
use Emma\Http\HttpStatus;
use Emma\Http\Response\Response;

class JsonResponse extends ControllerPlugin
{
    /**
     * @param array $data
     * @param int $status
     * @return Response
     * @throws \Exception
     */
    public function __invoke(array $data = array(), int $status = HttpStatus::HTTP_OK)
    {
        return $this->getController()
            ->getResponse()
            ->setResponseCode($status)
            ->setJson($data);
    }
}