<?php

namespace Emma\App\View\Html\Form;

use Emma\App\View\Html\Div;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 1/25/2016
 * Time: 5:19 AM
 */
class Alerts extends Div
{
    const SUCCESS = "alert-success";
    const WARNING = "alert-warning";
    const DANGER = "alert-danger";
    const INFO = "alert-info";

    /**
     * @param string $text
     * @param array $attributes
     * @param string $type
     */
    function __construct($text = "", array $attributes = [], $type = self::INFO)
    {
        parent::__construct($attributes, $text);
        $this->addClass("alert")
            ->setType($type)
            ->addAttribute('role', 'alert')
            ->dismissibleAlerts(false);
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->addClass($type);
        return $this;
    }

    /**
     * @param bool|true $dismissible
     * @return $this
     */
    public function dismissibleAlerts($dismissible = true)
    {
        $this->addAttribute('dismissible', $dismissible);
        return $this;
    }

    /**
     * @return string
     */
    public function getHTML()
    {
        $isDismissible = $this->getAttributeById("dismissible");
        $this->removeAttribute("dismissible");
        if ($isDismissible) {
            $this->addClass("alert-dismissible");
            $text = "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <i class='fa fa-close'></i></button>";
            $text .= $this->getContent();
            $this->setContent($text);
        }
        return parent::getHTML();
    }

}