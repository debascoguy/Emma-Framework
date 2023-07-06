<?php

namespace Emma\App\ServiceManager;

use Emma\App\Config;

class Escaper extends \Laminas\Escaper\Escaper
{
    public function __construct()
    {
        parent::__construct(Config::getInstance()->charset);
    }

    public function escapeAllHtml(array $params = array()): array
    {
        $escapedParams = array();
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $escapedParams[$key] = $this->escapeAllHtml($value);
            } else {
                $escapedParams[$key] = $this->escapeHtml($value);
            }
        }

        return $escapedParams;
    }

    public function escapeAllCss(array $params = array()): array
    {
        $escapedParams = array();
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $escapedParams[$key] = $this->escapeAllCss($value);
            } else {
                $escapedParams[$key] = $this->escapeCss($value);
            }
        }

        return $escapedParams;
    }

    public function escapeAllJs(array $params = array()): array
    {
        $escapedParams = array();
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $escapedParams[$key] = $this->escapeAllJs($value);
            } else {
                $escapedParams[$key] = $this->escapeJs($value);
            }
        }

        return $escapedParams;
    }

    public function escapeAllUrl(array $params = array()): array
    {
        $escapedParams = array();
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $escapedParams[$key] = $this->escapeAllUrl($value);
            } else {
                $escapedParams[$key] = $this->escapeUrl($value);
            }
        }

        return $escapedParams;
    }

}