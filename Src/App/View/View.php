<?php

namespace Emma\App\View;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 8/21/2017
 * Time: 7:11 AM
 */
interface View
{
    /**
     * @return array|string
     */
    public function getData(): array|string;

    /**
     * @param array|string $data
     * @return \Emma\App\View\Service\ViewHelper
     */
    public function setData(array|string $data);

    /**
     * @return string
     */
    public function getRouteString(): string;

    /**
     * @param string $routeString
     * @return View
     */
    public function setRouteString(string $routeString);

    /**
     * @return boolean
     */
    public function isJson(): bool;

    /**
     * @param boolean $json
     * @return static
     */
    public function setJson(bool $json = true): static;

    /**
     * @return boolean
     */
    public function isRawOutput(): bool;

    /**
     * @param boolean $rawOutput
     * @return View
     */
    public function setRawOutput(bool $rawOutput): View;
}