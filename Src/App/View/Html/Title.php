<?php
namespace Emma\App\View\Html;

class Title extends Tag
{
    function __construct($content = "", array $attributes = [])
    {
        parent::__construct("title", $attributes, $content);
    }


}
