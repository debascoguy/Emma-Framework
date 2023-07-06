<?php
namespace Emma\App\View\HelperPlugin;
use Emma\App\Config;
use Emma\App\ServiceManager\Escaper;

/**
 * Class Escape
 */
class Escape extends BaseViewHelper
{
    /**
     * @param string $string
     * @return string
     */
    public function __invoke(string $string): string
    {
        return $this->e($string);
    }

    /**
     * @param string $string
     * @return string
     */
    public function e(string $string): string
    {
        return (new Escaper())->escapeHtml($string);
    }
}
