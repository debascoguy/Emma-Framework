<?php
namespace Emma\App\Controller\Plugin;
use Emma\App\ServiceManager\Escaper;
use Emma\App\View\Service\ViewHelper;


class Output extends ControllerPlugin
{
    /**
     * @param $html
     * @return ViewHelper
     */
    public function __invoke($html): ViewHelper
    {
        $ViewModel = new ViewHelper();
        $ViewModel->setData((new Escaper())->escapeHtml($html))->setRawOutput(true);
        return $ViewModel;
    }

}