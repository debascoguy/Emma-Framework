<?php
namespace Emma\App\View\Html\Form;

/**
 * Class Select
 */
class Select extends FormTag
{

    /**
     * @param array $attributes
     * @param array $options
     * @param null $selected
     */
    public function __construct(array $attributes = array(), $options = array(), $selected = null)
    {
        parent::__construct("select", $attributes, "");
        $this->addOptions($options, $selected);
    }

    /**
     * @param Option $option
     * @param null $selected
     * @return Select
     */
    public function addOption(Option $option, $selected = null)
    {
        return $this->addOptions([$option], $selected);
    }

    /**
     * @param array $options
     * @param null $selected
     * @return $this
     * @throws \Emma\App\ErrorHandler\Exception\BaseException
     */
    public function addOptions(array $options = [], $selected = null)
    {
        foreach ($options as $key => $value) {
            if ($value instanceof Option) {
                if ($selected == $value->getAttributeById("value")){
                    $value->addAttribute("selected", "selected");
                }
                $this->addElement($value);
            } 
            else {
                $attribute = array("value" => $key);
                if ($selected==$key){
                    $attribute["selected"] = "selected";
                }
                $this->addElement(new Option($value, $attribute));
            }
        }
        return $this;
    }

}