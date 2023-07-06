<?php
namespace Emma\App\View\Html\Form;

/**
 * Class Input
 */
class Input extends FormTag
{

    /**
     * @param string $type
     * @param array $attributes
     * @param string $content
     */
    public function __construct($type = "text", array $attributes = [], $content = "")
    {
        $attributes["type"] = $type;
        parent::__construct("input", $attributes, $content);
    }

    
}