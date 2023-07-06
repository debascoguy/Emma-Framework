<?php
/**
 * Created By: Ademola Aina
 * Email: debascoguy@gmail.com
 */


namespace Emma\App\View\Html\Form;


use Emma\App\View\Html\Tag;

class FormTag extends Tag
{

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