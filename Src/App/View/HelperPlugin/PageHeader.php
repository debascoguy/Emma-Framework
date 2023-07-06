<?php
namespace Emma\App\View\HelperPlugin;
/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 8/26/2017
 * Time: 8:37 AM
 */
class PageHeader extends BaseViewHelper
{

    public function __invoke($pageHeader = "")
    {
        return $this->setPageHeader($pageHeader);
    }

    /**
     * @param $pageHeader
     * @return $this
     */
    public function setPageHeader($pageHeader)
    {
        $template = $this->getTemplate();
        $template->setPageHeader($pageHeader);
        return $this;
    }
}