<?php

namespace Emma\App\View\Html;


use Emma\App\ErrorHandler\Exception\BaseException;
use Emma\App\ServiceManager\Escaper;
use Emma\Common\Utils\StringManagement;
use Emma\Security\Xss;

class Tag implements \Emma\App\View\Html
{
    //id=>'', class=>''
    protected $attributes = array(), $tag = "", $content = "";
    protected $encodeContent = true;

    /**
     * @var array recursive adding tags inside tag according to HTML constructs...
     */
    protected $childrenTags = array();

    /**
     * @var bool
     * Flip TRUE is you want the tags to built by applying content variable first before attaching the children tags HTML
     * FLip FALSE for reverse order.
     */
    public $contentBeforeChildrenTags = true;

    /**
     * @var bool
     */
    public $shortTag = false;

    /**
     * @param string $tag
     * @param array $attribute
     * @param string $content
     * @param bool $isShortTag
     * @param bool $contentBeforeChildrenTags
     */
    public function __construct(
        string $tag,
        array $attribute = array(),
        string $content = "",
        bool $isShortTag = false,
        bool $contentBeforeChildrenTags = true)
    {
        $this->tag = $tag;
        $this->attributes = $attribute;
        $this->content = $content;
        $this->contentBeforeChildrenTags = $contentBeforeChildrenTags;

        $shortTags = ["img", "br", "link", "meta"];
        $this->setShortTag($isShortTag || in_array($this->tag, $shortTags));
    }

    /**
     * @return bool
     */
    public function isContentBeforeChildrenTags(): bool
    {
        return $this->contentBeforeChildrenTags;
    }

    /**
     * @param bool $contentBeforeChildrenTags
     * @return Tag
     */
    public function setContentBeforeChildrenTags(bool $contentBeforeChildrenTags): static
    {
        $this->contentBeforeChildrenTags = $contentBeforeChildrenTags;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShortTag(): bool
    {
        return $this->shortTag;
    }

    /**
     * @param bool $shortTag
     * @return Tag
     */
    public function setShortTag(bool $shortTag): static
    {
        $this->shortTag = $shortTag;
        return $this;
    }

    /**
     * @param $attr
     * @param $value
     * @return $this
     */
    public function addAttribute($attr, $value): static
    {
        if ($attr == "html" || $attr == "class" || $attr == "style") {
            $this->attributes[$attr][] = $value;
        } else {
            $this->attributes[$attr] = $value;
        }
        if ($attr == "type" && $value == "hidden") {
            $this->attributes['hide'] = true;
        }
        return $this;
    }
    
    
    /**
     * @param $attr
     * @return string|null
     */
    public function getAttributeById($attr): ?string
    {
        if (array_key_exists($attr, $this->attributes)) {
            return $this->attributes[$attr];
        }

        return null;
    }

    /**
     * @param array $excludeAttribute
     * @return string
     */
    public function getAttributes(array $excludeAttribute = array()): string
    {
        $attribute = "";
        $escaper = new Escaper();
        foreach ($this->attributes as $attr => $value) {
            if ($attr == "hide" || StringManagement::in_arrayi($attr, $excludeAttribute)) {
                continue;
            }

            if (is_array($value)) {
                $value = implode(" ", $value);
            }

            $attribute .= $escaper->escapeHtmlAttr($attr . ' = "' . $value . '" ');
        }
        return $attribute;
    }

    /**
     * @param array $attribs
     * @return string
     */
    public function getAttributesExcept(array $attribs = array()): string
    {
        return $this->getAttributes($attribs);
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param $attr
     * @return $this
     */
    public function removeAttribute($attr): static
    {
        unset($this->attributes[$attr]);
        return $this;
    }

    /**
     * @return $this
     */
    public function removeDefaultAttributes(): static
    {
        $this->attributes = array();
        return $this;
    }

    /**
     * @param $inlineCss
     * @return $this
     */
    public function addStyle($inlineCss): static
    {
        return $this->addAttribute("style", $inlineCss);
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id): static
    {
        return $this->addAttribute("id", $id);
    }

    /**
     * @param $class
     * @return $this
     */
    public function addClass($class): static
    {
        return $this->addAttribute('class', $class);
    }

    /**
     * @return $this
     */
    public function removeDefaultClasses(): static
    {
        $this->attributes['class'] = array();
        return $this;
    }

    /**
     * @param string $html
     * @return $this
     */
    public function addHtml(string $html = ""): static
    {
        $this->addNewTag("span", [], $html)->setEncodeContent(false);
        return $this;
    }

    /**
     * @param $tag
     * @param array $attributes
     * @param string $content
     * @return $this
     * @throws BaseException
     */
    public function addNewTag($tag, array $attributes = [], string $content = ""): Tag|static
    {
        $element = new Tag($tag, $attributes, $content);
        $this->addElement($element);
        return $element;
    }

    /**
     * @param $element
     * @return $this
     * @throws BaseException
     */
    public function addElement($element): static
    {
        if (is_object($element)) {
            $this->childrenTags[] = $element;
            return $this;
        }
        throw new BaseException("Invalid Element Type");
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Form\Input
     * @throws BaseException
     */
    public function addInput(array $attributes = [], string $content = ""): Form\Input
    {
        $input = new Form\Input("text", $attributes, $content);
        $this->addElement($input);
        return $input;
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Form\Input
     * @throws BaseException
     */
    public function addEmail(array $attributes = [], string $content = ""): Form\Input
    {
        $input = new Form\Input("email", $attributes, $content);
        $this->addElement($input);
        return $input;
    }

    /**
     * @param array $attributes
     * @param string $content
     * @return Form\Input
     * @throws BaseException
     */
    public function addPassword(array $attributes = [], string $content = ""): Form\Input
    {
        $input = new Form\Input("password", $attributes, $content);
        $this->addElement($input);
        return $input;
    }

    /**
     * @param array $attributes
     * @param array $options
     * @param string|null $selectedKey
     * @return Form\Select
     * @throws BaseException
     */
    public function addSelect(array $attributes = [], array $options = [], string $selectedKey = null): Form\Select
    {
        $select = new Form\Select($attributes, $options, $selectedKey);
        $this->addElement($select);
        return $select;
    }

    /**
     * @param string $content
     * @param array $attributes
     * @return Form\Label
     * @throws BaseException
     */
    public function addLabel(string $content = "", array $attributes = []): Form\Label
    {
        $label = new Form\Label($content, $attributes);
        $this->addElement($label);
        return $label;
    }

    /**
     * @param $id
     * @return $this
     */
    public function removeTagById($id): static
    {
        unset($this->childrenTags[$id]);
        return $this;
    }

    /**
     * @return array|Tag[]
     */
    public function getChildrenTags(): array
    {
        return $this->childrenTags;
    }

    /**
     * @param array $childrenTags
     * @return $this
     */
    public function setChildrenTags(array $childrenTags): static
    {
        $this->childrenTags = $childrenTags;
        return $this;
    }

    /**
     * @param $number
     * @return $this
     */
    final public function setTabIndex($number = 0): static
    {
        return $this->addAttribute('tabindex', $number);
    }

    /**
     * @return $this
     */
    final public function noTabIndex(): static
    {
        return $this->removeAttribute('tabindex');
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return Tag
     */
    public function setTag(string $tag): static
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        $escaper = new Escaper();
        return $this->isEncodeContent() ?$escaper->escapeHtml($this->content) : $this->content;
    }

    /**
     * @param string $content
     * @return Tag
     */
    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param $title
     * @return $this
     * @throws BaseException
     */
    public function setTitle($title): static
    {
        $this->addAttribute("title", $title);
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isEncodeContent(): bool
    {
        return $this->encodeContent;
    }
    
    /**
     * @param bool $encodeContent
     * @return $this
     */
    public function setEncodeContent(bool $encodeContent): static
    {
        $this->encodeContent = $encodeContent;
        return $this;
    }
    
    /**
     * @param string $tooltip
     * @param string $position
     * @return $this
     */
    public function setToolTipText(string $tooltip = "", string $position = "auto left"): static
    {
        $this->addAttribute("data-toggle", "tooltip");
        $this->addAttribute("data-placement", $position);   //left, right, top, bottom, "auto" makes the browser decides if that position is good or choose another position.
        $this->addAttribute("title", $tooltip);
        return $this;
    }

    /**
     * @param string $message
     * @param string $titleHeader
     * @param string $position
     * @return $this
     */
    public function setPopOverOnClick(string $message = "", string $titleHeader = "", string $position = "auto left"): static
    {
        $this->addAttribute("data-toggle", "popover");
        $this->addAttribute("data-placement", $position);
        $this->addAttribute("data-content", $message);
        $this->addAttribute("data-original-title", $titleHeader);
        $this->addAttribute("title", "");
        return $this;
    }

    /**
     * @param string $message
     * @param string $titleHeader
     * @param string $position
     * @return $this
     */
    public function setPopOverOnHover(string $message = "", string $titleHeader = "", string $position = "auto top"): static
    {
        $this->setPopOverOnClick($message, $titleHeader, $position);
        $this->addAttribute("data-trigger", "hover");
        return $this;
    }

    /**
     * @param bool $shortTag
     * @return string
     */
    public function startTag(bool $shortTag = false): string
    {
        return sprintf("%s%s %s%s", "<", $this->tag, $this->getAttributes(), ($shortTag) ? "/>" : ">") . PHP_EOL;
    }

    /**
     * @return string
     */
    public function endTag(): string
    {
        return sprintf("%s%s%s", "</", $this->tag, ">") . PHP_EOL;
    }

    /**
     * @return string
     * @throws BaseException
     */
    public function getHTML(): string
    {
        if (!empty($this->tag)) {
            $childrenTags = $this->getChildrenTags();
            $childrenTagsHtml = "";
            foreach ($childrenTags as $childTag) {
                $childrenTagsHtml .= $childTag->getHTML();
            }
            
            $tagBody = $this->contentBeforeChildrenTags ? $this->getContent() . $childrenTagsHtml : $childrenTagsHtml . $this->getContent();

            if ($this->isShortTag()) {
                return $this->startTag(true) . $tagBody;
            }
            return  sprintf("%s%s%s", $this->startTag(), $tagBody, $this->endTag()) . PHP_EOL;
        }
        throw new BaseException("Invalid Html Builder Request");
    }

    /**
     * @return string
     * @throws BaseException
     */
    public function __toString(): string
    {
        return $this->getHTML();
    }

    /**
     * @echo HTML;
     * @throws BaseException
     */
    public function render(): string
    {
        echo $this->__toString();
    }

}
