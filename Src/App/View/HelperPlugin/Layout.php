<?php
namespace Emma\App\View\HelperPlugin;
/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Layout extends BaseViewHelper
{
    /**
     * @param $name
     * @param string $pageTitle
     * @return Layout
     */
    public function __invoke($name, string $pageTitle = ""): static
    {
        return $this->setTemplateLayout($name, $pageTitle);
    }

    /**
     * @param $name
     * @param string $pageTitle
     * @return $this
     */
    public function setTemplateLayout($name, string $pageTitle = ""): static
    {
        $template = $this->getTemplate();
        $template->setLayoutName($name);
        $template->setPageTitle($pageTitle);
        return $this;
    }

    /**
     * @param $pageHeader
     * @return $this
     */
    public function setPageHeader($pageHeader): static
    {
        $template = $this->getTemplate();
        $template->setPageHeader($pageHeader);
        return $this;
    }
}
