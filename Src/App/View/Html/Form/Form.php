<?php
namespace Emma\App\View\Html\Form;

use Emma\App\Config;
use Emma\App\ServiceManager\Escaper;
use Emma\App\ServiceManager\Token;
use Emma\App\View\Html\Div;
use Emma\App\View\Html\Html;
use Emma\App\View\Html\Tag;
use Emma\Security\Xss;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 2/16/2019
 * Time: 12:04 PM
 */
class Form extends FormTag
{
    /** FORM method of action */
    const METHOD_POST = "POST";
    const METHOD_GET = "GET";
    const METHOD_PUT = "PUT";

    function __construct(array $attributes = [])
    {
        $attributes["enctype"] = isset($attributes["enctype"]) ? $attributes["enctype"] : "multipart/form-data";
        $attributes["action"] = isset($attributes["action"]) ? $attributes["action"] : "#";
        $attributes["method"] = isset($attributes["method"]) ? $attributes["method"] : self::METHOD_POST;
        parent::__construct("form", $attributes);
    }

    /**
     * @param array $attributes
     * @return Form
     */
    public static function start(array $attributes = [])
    {
        return new self($attributes);
    }

    /**
     * @return $this
     */
    public function end()
    {
        $appConfig = Config::getInstance();
        if ($appConfig->enableCsrfValidation) {
            $token = Token::getCsrfToken();
            $this->addHiddenValues(array($appConfig->csrfParamName => $token));
        }
        return $this;
    }
    
    /**
     * @param string $value
     * @return $this
     */
    public function setMethod($value = self::METHOD_POST)
    {
        return $this->addAttribute("method", $value);
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setAction($url = "#")
    {
        $this->addAttribute("action", $url);
        return $this;
    }

    /**
     * @param $attr
     * @param $value
     * @return $this
     */
    public function addFormAttribute($attr, $value)
    {
        $this->addAttribute($attr, $value);
        return $this;
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Tag
     */
    public function addDiv(array $attributes = [], $content = "")
    {
        $div = new Div($attributes, $content);
        $this->addElement($div);
        return $div;
    }
    
    /**
     * @param $label
     * @param int $type
     * @param array $attribute
     * @param bool|false $underline
     * @return $this
     */
    public function addHeader($label, $type = 2, array $attribute = [], $underline = false)
    {
        if ($underline) {
            if (is_array($attribute["class"])) {
                $attribute["class"][] = " underlined ";
            } else {
                $attribute["class"] .= " underlined ";
            }
        }
        return $this->addNewTag("h$type", $attribute, $label); //h1, h2, h3, h4, h5, h6
    }

    /**
     * @param array $vals
     */
    public function addHiddenValues(array $vals)
    {
        foreach ($vals as $name => $value) {
            $this->addElement(Html::inputHidden(['name' => $name, 'value' => $value]));
        }
    }

}