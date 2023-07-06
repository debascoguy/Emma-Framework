<?php

namespace Emma\App\View\Html;

use Emma\App\ServiceManager\Escaper;
use Emma\App\ServiceManager\Token;
use Emma\App\View\Html\Form\Input;
use Emma\App\View\Html\Form\Label;
use Emma\App\View\Html\Form\Option;
use Emma\App\View\Html\Form\Select;
use Emma\App\Config;
use Emma\Security\Xss;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 2/15/2019
 * Time: 7:30 PM
 *
 * @PHP MAGIC function __callStatic() will handle this tags below.
 *
 * @phpDoc If NOT created, Feel free to ADD any html tag using the format as used below and you're good to go.
 *
 * @method Tag p($tag = "p", array $attribute = array(), $content = "", $isShortTag = false, $contentBeforeChildrenTags = true);
 * @method Tag span($tag = "span", array $attribute = array(), $content = "", $isShortTag = false, $contentBeforeChildrenTags = true);
 * @method Tag i($tag = "i", array $attribute = array(), $content = "", $isShortTag = false, $contentBeforeChildrenTags = true);
 * @method Tag u($tag = "u", array $attribute = array(), $content = "", $isShortTag = false, $contentBeforeChildrenTags = true);
 *
 *
 */
class Html {

    /**
     * @param $tag
     * @param array $attribute
     * @param string $content
     * @param bool|true $isShortTag
     * @param bool|true $contentBeforeChildrenTags
     * @return Tag
     */
    public static function tag($tag, array $attribute = [], $content = "", $isShortTag = true, $contentBeforeChildrenTags = true) {
        return new Tag($tag, $attribute, $content, $isShortTag, $contentBeforeChildrenTags);
    }

    /**
     * @param string $content
     * @param array $attributes
     * @return Head
     */
    public static function head($content = "", array $attributes = []) {
        return new Head($content, $attributes);
    }

    /**
     * @param $link
     * @param array $attributes
     * @return Css
     */
    public static function css($link, array $attributes = []) {
        return new Css($link, $attributes);
    }

    /**
     * @param $content
     * @param array $attributes
     * @return Css
     */
    public static function cssInternal($content, array $attributes = []) {
        return new Css(null, $attributes, true, $content);
    }

    /**
     * @param $scriptSource
     * @param array $attributes
     * @param string $content
     * @return Js
     */
    public static function js($scriptSource, array $attributes = [], $content = "") {
        return new Js($scriptSource, $attributes, $content);
    }

    /**
     * @param $content
     * @param array $attributes
     * @return Js
     */
    public static function jsInternal($content, array $attributes = []) {
        return new Js(null, $attributes, $content);
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Tag
     */
    public static function body(array $attributes = [], $content = "") {
        return new Body($attributes, $content);
    }

    /**
     * @param string $url
     * @param array $attributes
     * @param string $content
     * @return Tag
     */
    public static function a($url = "", array $attributes = [], $content = "") {
        $attributes["href"] = $url;
        $element = new Tag("a", $attributes, $content);
        return $element;
    }

    /**
     * @param string $url
     * @param array $attributes
     * @param string $content
     * @return Tag
     */
    public static function button($content = "", array $attributes = [], $url = "") {
        if (!empty($url)) {
            $attributes["onclick"] = $url;
        }
        $element = new Tag("button", $attributes, $content);
        return $element;
    }
    
    /**
     * @param array $attributes
     * @param string $content
     * @return Tag
     */
    public static function submit($content = "", array $attributes = []) {
        $attributes["type"] = "submit";
        $element = new Tag("button", $attributes, $content);
        return $element;
    }

    /**
     * @param array $attributes
     * @return Tag
     */
    public static function br(array $attributes = []) {
        return new Tag("br", $attributes);
    }

    /**
     * @param string $type
     * @param array $attributes
     * @param string $content
     * @return Input
     */
    public static function inputField($type = "text", array $attributes = [], $content = "") {
        return new Input($type, $attributes, $content);
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Input
     */
    public static function inputHidden(array $attributes = [], $content = "") {
        return new Input("hidden", $attributes, $content);
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Input
     */
    public static function inputEmail(array $attributes = [], $content = "") {
        return new Input("email", $attributes, $content);
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Input
     */
    public static function inputPassword(array $attributes = [], $content = "") {
        return new Input("password", $attributes, $content);
    }

    /**
     * @param array $attributes
     * @param string $content
     * @param null $selectedKey
     * @return Select
     */
    public static function select(array $attributes = [], $content = "", $selectedKey = null) {
        return new Select($attributes, $content, $selectedKey);
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Option
     */
    public static function option($content, array $attributes = []) {
        return new Option($content, $attributes);
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Option
     */
    public static function label($content, array $attributes = []) {
        return new Label($content, $attributes);
    }

    /**
     * @return string
     */
    public static function createCsrfMetaTags() {
        $html = "";
        $config = Config::getInstance();
        if ($config->enableCsrfValidation) {
            $html = self::tag('meta', array('name' => 'csrf_param', 'content' => $config->csrfParamName)) . PHP_EOL;
            $html .= self::tag('meta', array('name' => $config->csrfParamName, 'content' => Token::getCsrfToken())) . PHP_EOL;
        }
        return $html;
    }

    /**
     * @param $tagName
     * @param $argument
     * @return Tag
     */
    public static function __callStatic($tagName, $argument) {
        $attributes = $argument[0];
        $content = $argument[1];
        $isShortTag = !empty($argument[2]);
        $contentBeforeChildrenTags = empty($argument[3]);
        return self::tag($tagName, $attributes, $content, $isShortTag, $contentBeforeChildrenTags);
    }

}
