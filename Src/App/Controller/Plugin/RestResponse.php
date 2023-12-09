<?php
namespace Emma\App\Controller\Plugin;

use Emma\Http\Response\ResponseInterface;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/2/2017
 * Time: 8:01 AM
 */
class RestResponse extends ControllerPlugin
{
    /**
     * @param array|string $data
     * @param int $status
     * @return ResponseInterface
     * @throws \Exception
     */
    public function __invoke(string $status, array|string|null $data, ?string $message = null, int $httpStatus = 200): ResponseInterface
    {
        $response = [
            "status" => $status, 
            "message" => $message, 
            "data" => $data, 
            "timestamp" => date('Y-m-d H:i:s')
        ];
        return $this->getController()
            ->getResponse()
            ->setResponseCode($httpStatus)
            ->setJson($response);
    }

}