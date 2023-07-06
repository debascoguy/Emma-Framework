<?php
namespace Emma\App\View\Html\Form;

use Emma\App\View\Html\Tag;

/**
 * Class Input
 */
class TextArea extends Tag
{

    /**
     * @param string $type
     * @param array $attributes
     * @param string $content
     */
    public function __construct(array $attributes = [], $content = "")
    {
        parent::__construct("textarea", $attributes, $content);
    }

    /**
     * @param $rowSize
     * @return $this
     */
    public function setRowSize($rowSize)
    {
        return $this->addAttribute("row", $rowSize);
    }

    /**
     * @param $columnSize
     * @return $this
     */
    public function setColumnSize($columnSize)
    {
        return $this->addAttribute("col", $columnSize);
    }
    
    /**
     * @param string $name
     * @return $this
     */
    public function setId($name)
    {
        return $this->addAttribute("id", $name);
    }
    
    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->addAttribute("id", $name)->addAttribute("name", $name);
    }
    
    /**
     * @param string $placeholder
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        return $this->addAttribute("Placeholder", $placeholder);
    }
    

    /**
     * @return $this
     */
    public function isRequired() 
    {
        return $this->addAttribute("required", "required");
    }

    
}