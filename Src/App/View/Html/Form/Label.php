<?php
namespace Emma\App\View\Html\Form;

use Emma\App\View\Html\Tag;

/**
 * Class Label
 */
class Label extends Tag
{
    /**
     * @param string $content
     * @param array $attributes
     */
    public function __construct($content, array $attributes = ["for" => ""])
    {
        parent::__construct("label", $attributes, $content);
    }
    
    /**
     * @param string $forName
     * @return $this
     */
    public function setFor($forName)
    {
        return $this->addAttribute("for", $forName);
    }

}