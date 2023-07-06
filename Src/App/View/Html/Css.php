<?php
namespace Emma\App\View\Html;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 2/16/2019
 * Time: 8:37 AM
 */
class Css extends Tag
{
    /**
     * @var bool
     */
    protected $internal = false;


    /**
     * @param $link
     * @param array $attribute
     * @param bool|false $inline
     * @param string $content
     */
    function __construct($link, array $attribute = [], $inline = false, $content = "")
    {
        $attributes["rel"] = "stylesheet";
        $attributes["href"] = $link;
        parent::__construct("link", $attribute, $content);
        $this->setShortTag(true);
        $this->setInternal($inline);
    }

    /**
     * @return boolean
     */
    public function isInternal()
    {
        return $this->internal;
    }

    /**
     * @param boolean $internal
     * @return Css
     */
    public function setInternal($internal)
    {
        $this->internal = $internal;
        if ($this->internal) {
            $this->setTag("style");
            $this->setShortTag(false);
            unset($this->attributes["rel"]);
            unset($this->attributes["href"]);
        }
        return $this;
    }


}