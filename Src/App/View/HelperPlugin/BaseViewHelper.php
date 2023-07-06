<?php

namespace Emma\App\View\HelperPlugin;

use Emma\App\View\PluginInterface;
use Emma\App\View\Service\Template;
use Emma\App\View\Service\ViewEngine;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
abstract class BaseViewHelper implements PluginInterface
{
    /**
     * @var Template|null
     */
    protected ?Template $template = null;

    /**
     * @var ViewEngine|null
     */
    protected ?ViewEngine $viewEngine = null;

    /**
     * @return Template|null
     */
    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    /**
     * @param Template $template
     * @return BaseViewHelper
     */
    public function setTemplate(Template $template): static
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return ViewEngine|null
     */
    public function getViewEngine(): ?ViewEngine
    {
        return $this->viewEngine;
    }

    /**
     * @param ViewEngine $viewEngine
     * @return $this|BaseViewHelper
     */
    public function setViewEngine(ViewEngine $viewEngine): static
    {
        $this->viewEngine = $viewEngine;
        return $this;
    }

}