<?php
namespace Emma\App\View\Html;
/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 2/16/2019
 * Time: 8:33 AM
 */
class Js extends Tag
{
    /**
     * @param $scriptSource
     * @param array $attribute
     * @param string $content
     */
    function __construct($scriptSource, array $attribute = [], $content = "")
    {
        if (!empty($scriptSource)) {
            $attributes["src"] = $scriptSource;
        }
        parent::__construct("script", $attribute, $content);
    }


}