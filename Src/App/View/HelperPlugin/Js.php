<?php
namespace Emma\App\View\HelperPlugin;

use Emma\App\ErrorHandler\Exception\BaseException;
use Emma\App\ServiceManager\Escaper;

class Js extends BaseViewHelper
{
    /** Capture Modes */
    const MODE_APPEND = 'append';
    const MODE_PREPEND = 'prepend';

    /**
     * List of JS files for the page.
     * @var array
     */
    protected array $files = array();

    /**
     * Captured javascript code.
     * @var array
     */
    protected array $captures = array();

    /**
     * True when a capture is in progress.
     * @var bool
     */
    protected bool $capturing = false;

    /**
     * @var null
     */
    protected $version = null;

    /**
     * @return string
     */
    public function render(): string
    {
        $string = '';
        $escaper = new Escaper();
        foreach ($this->files as $file) {
            if (is_null($file["version"])){
                $string .= sprintf('<script src="%s"></script>', $escaper->escapeJs($file["file"])).PHP_EOL;
            }
            else{
                $string .= sprintf('<script src="%s_=%s"></script>', $escaper->escapeJs($file["file"]), $file["version"]).PHP_EOL;
            }
        }
        $string .= $this->renderCaptures();
        return $string;
    }

    /**
     * @return string
     */
    public function renderCaptures()
    {
        $string = '';
        foreach ($this->captures as $capture) {
            $string .= sprintf('<script>%s</script>', $capture['content']);
            $string .= "\n";
        }
        return $string;
    }

    /**
     *  Adds a JS file to the list.
     * @param $file
     * @param mixed $version
     * @return $this
     */
    public function add($file, $version = null)
    {
        $this->files[] = array(
            "file" => $file,
            "version" => $version
        );
        return $this;
    }

    /**
     * Adds a file to the beginning of the list.
     * @param string $file
     * @param mixed $version
     * @return $this
     */
    public function prepend($file, $version = null)
    {
        array_unshift($this->files, array("file"=>$file, "version"=>$version));
        return $this;
    }

    /**
     * Starts capturing javascript.
     */
    public function captureStart()
    {
        if ($this->capturing) {
            throw new BaseException('Cannot nest javascript captures.');
        }
        $this->capturing = true;
        ob_start();
    }

    /**
     * Stops capturing javascript.
     * @param string $mode
     * @return Js
     */
    public function captureEnd($mode = self::MODE_APPEND)
    {
        $content = ob_get_clean();
        $this->capturing = false;
        return $this->addInlineScript($content, $mode);
    }
    
    /**
     * @param string $content
     * @param string $mode
     * @return $this
     */
    public function addInlineScript($content, $mode = self::MODE_APPEND)
    {
        $capture = array(
            'content' => $content,
        );
        
        if ($mode == self::MODE_APPEND) {
            $this->captures[] = $capture;
        } else {
            array_unshift($this->captures, $capture);
        }
        return $this;
    }

    /**
     * @param string $html
     * @return string
     */
    public function extractJs($html)
    {
        $script = '';
        $files = array();
        $regex = '#<script(?:.*?(?:src=[\'"](.+?)[\'"].*?)?)>(.*?)</script>#is';
        if (preg_match_all($regex, $html, $matches)) {
            if (isset($matches[2])) {
                foreach ($matches[1] as $match) {
                    $files[] = $match;
                }
                $scriptMatches = $matches[2];
            } else {
                $scriptMatches = $matches[1];
            }
            foreach ($scriptMatches as $match) {
                $script .= $match;
            }
        }

        $baseUri = (string)$this->getTemplate()->helper('basePath');

        foreach ($files as $file) {
            if (empty($file)) {
                continue;
            }

            $this->add(str_replace($baseUri . '/', '', $file));
        }

        if ($script) {
            $this->captureStart();
            echo $script;
            $this->captureEnd();
        }

        return preg_replace($regex, '', $html);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param array $files
     * @return Js
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

}
