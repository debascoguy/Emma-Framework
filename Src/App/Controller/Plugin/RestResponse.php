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
    public function __invoke(array|string $data, int $status = 200): ResponseInterface
    {
        return $this->send($data, $status);
    }

    /**
     * @param array|string $data
     * @param int $status
     * @return ResponseInterface
     * @throws \Exception
     */
    public function send(array|string $data, int $status = 200): ResponseInterface
    {
        $this->getController()->getResponse()->setResponseCode($status);
        $_result = ($status != 200) ? array('status' => $status, 'error' => $data) : array_merge(['status' => $status], (array)$data);
        $_result["result_type"] = "json";
        $_result["timestamp"] = date('Y-m-d H:i:s');
        return $this->getController()->json($_result);
    }

}