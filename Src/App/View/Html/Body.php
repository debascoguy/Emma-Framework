<?php
namespace Emma\App\View\Html;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 2/16/2019
 * Time: 6:23 AM
 */
class Body extends Tag
{
    function __construct(array $attributes = [], $content = "")
    {
        parent::__construct("body", $attributes, $content);
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return $this
     */
    public function addDiv(array $attributes = [], $content = "")
    {
        return $this->addNewTag("div", $attributes, $content);
    }


}
