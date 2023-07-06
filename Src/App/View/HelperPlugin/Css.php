<?php
namespace Emma\App\View\HelperPlugin;

use Emma\App\ServiceManager\Escaper;

class Css extends BaseViewHelper
{
    /**
     * List of CSS files for the page.
     * @var array
     */
    protected array $files = array();

    /**
     * @return string
     */
    public function render(): string
    {
        $string = '';
        $escaper = new Escaper();
        foreach ($this->files as $file) {
            if (is_null($file["version"])){
                $string .= sprintf('<link rel="stylesheet" type="text/css" href="%s" media="%s"/>', $escaper->escapeCss($file['file']), $file['media']).PHP_EOL;
            }
            else{
                $string .= sprintf('<link rel="stylesheet" type="text/css" href="%s?_=%s" media="%s"/>', $escaper->escapeCss($file['file']), $file["version"], $file['media']).PHP_EOL;
            }
        }
        return $string;
    }

    /**
     * Adds a CSS file to the list.
     * @param string $file
     * @param string $media
     * @param mixed|null $version
     * @return $this
     */
    public function add(string $file, string $media = 'screen', mixed $version = null)
    {
        $this->files[] = array(
            'file' => $file,
            'media' => $media,
            'version' => $version
        );
        return $this;
    }

    /**
     * Adds a file to the beginning of the list.
     * @param string $file
     * @param string $media
     * @param mixed|null $version
     * @return Css
     */
    public function prepend(string $file, string $media = 'screen', mixed $version = null): static
    {
        array_unshift($this->files, array(
            'file' => $file,
            'media' => $media,
            'version' => $version,
        ));
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param string $column
     * @return array
     */
    public function getFiles(string $column = ""): array
    {
        if (!empty($column)) {
            return array_column($this->files, $column);
        }
        return $this->files;
    }

}
