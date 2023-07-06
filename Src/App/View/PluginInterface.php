<?php

namespace Emma\App\View;

use Emma\App\View\HelperPlugin\BaseViewHelper;
use Emma\App\View\HelperPlugin\PluginManager;
use Emma\App\View\Service\Template;
use Emma\App\View\Service\ViewEngine;

interface PluginInterface
{
    /**
     * @return Template
     */
    public function getTemplate();

    /**
     * @param Template $template
     * @return BaseViewHelper
     */
    public function setTemplate(Template $template);

    /**
     * @return ViewEngine
     */
    public function getViewEngine(): ?ViewEngine;

    /**
     * @param ViewEngine $viewEngine
     * @return $this
     */
    public function setViewEngine(ViewEngine $viewEngine): static;
}
