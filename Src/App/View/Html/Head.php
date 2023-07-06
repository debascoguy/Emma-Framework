<?php
namespace Emma\App\View\Html;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 2/16/2019
 * Time: 6:23 AM
 */
class Head extends Tag
{
    /**
     * @param array $attributes
     * @param string $content
     */
    function __construct($content = "", array $attributes = [])
    {
        parent::__construct("head", $attributes, $content, false, false);
    }

    /**
     * @param $content
     * @param array $attributes
     * @return $this
     */
    public function setTitle($content, array $attributes = [])
    {
        $this->childrenTags[] = new Title($content, $attributes);
        return $this;
    }

    /**
     * @param $scriptSource
     * @param array $attributes
     * @param string $content
     * @return $this
     */
    public function addJs($scriptSource, array $attributes = [], $content = "")
    {
        $script = new Js($scriptSource, $attributes, $content);
        $this->childrenTags[] = $script;
        return $this;
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return $this
     */
    public function addJsInternal(array $attributes = [], $content = "")
    {
        return $this->addJs(null, $attributes, $content);
    }

    /**
     * @param $link
     * @param array $attributes
     * @return $this
     */
    public function addCss($link, array $attributes = [])
    {
        $css = new Css($link, $attributes);
        $this->childrenTags[] = $css;
        return $this;
    }

    /**
     * @param $content
     * @param array $attributes
     * @return $this
     */
    public function addCssInternal($content, array $attributes = [])
    {
        $css = new Css(null, $attributes, true, $content);
        $this->childrenTags[] = $css;
        return $this;
    }


}
