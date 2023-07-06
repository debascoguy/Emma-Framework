<?php
namespace Emma\App\View\Html\Form;

use Emma\App\View\Html\Tag;

/**
 * Class Image
 */
class Image extends Tag
{

    const IMAGE_TYPE_IMG_RESPONSIVE = "img-responsive";
    const IMAGE_TYPE_IMG_THUMBNAIL = "img-thumbnail";
    const IMAGE_TYPE_IMG_CIRCLE = "img-circle";  //(not available in IE8)
    const IMAGE_TYPE_IMG_ROUNDED = "img-rounded"; //(not available in IE8)

    /**
     * @param string $html
     * @param array $attributes
     */
    public function __construct($html = "", array $attributes = [])
    {
        parent::__construct("img", $attributes, $html);
        $this->setType(self::IMAGE_TYPE_IMG_RESPONSIVE);
    }

    /**
     * @param $src
     * @return $this
     */
    public function setSource($src)
    {
        $this->addAttribute("src", $src);
        return $this;
    }

    /**
     * @param $alt
     * @return $this
     */
    public function setAltImage($alt)
    {
        $this->addAttribute("alt", $alt);
        return $this;
    }

    /**
     * @param $classType
     * @return $this
     */
    public function setType($classType)
    {
        $this->addClass($classType);
        return $this;
    }

}