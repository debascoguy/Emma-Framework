<?php
namespace Emma\App\View\Html;

class Div extends Tag
{
    function __construct(array $attributes = [], $content = "")
    {
        parent::__construct("div", $attributes, $content);
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return $this
     */
    public function addDiv(array $attributes = [], $content = "")
    {
        $Div = new self($attributes, $content);
        $this->addElement($Div);
        return $Div;
    }

}