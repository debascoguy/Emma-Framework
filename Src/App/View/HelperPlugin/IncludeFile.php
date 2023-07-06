<?php
namespace Emma\App\View\HelperPlugin;

class IncludeFile extends BaseViewHelper
{
    /**
     * @param string $name
     * @param array $data
     * @return string
     */
    public function __invoke($name, array $data = array())
    {
        $data = array_merge($this->getTemplate()->getData(), $data);
        return $this->getTemplate()->getViewEngine()->render($name, $data);
    }
}
