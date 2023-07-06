<?php
namespace Emma\App\View\Html\Form;

use Emma\App\View\Html\Tag;

/**
 * Class Option
 */
class Option extends Tag
{

    /**
     * @param string $content
     * @param array $attributes
     */
    public function __construct($content = "", array $attributes = [])
    {
        parent::__construct("option", $attributes, $content);
    }
}