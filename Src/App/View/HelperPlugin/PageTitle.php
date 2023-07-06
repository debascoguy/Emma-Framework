<?php
namespace Emma\App\View\HelperPlugin;
/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 8/21/2017
 * Time: 9:27 PM
 */
class PageTitle extends BaseViewHelper
{    
    public function __invoke($pageTitle = "")
    {
        return $this->setPageTitle($pageTitle);
    }

    /**
     * @param $pageTitle
     * @return $this
     */
    public function setPageTitle($pageTitle)
    {
        $template = $this->getTemplate();
        $template->setPageTitle($pageTitle);
        return $this;
    }
    
}
