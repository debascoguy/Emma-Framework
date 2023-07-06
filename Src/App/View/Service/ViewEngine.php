<?php

namespace Emma\App\View\Service;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 8/22/2017
 * Time: 12:47 PM
 */
class ViewEngine
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @var string[]
     */
    protected $paths;

    /**
     * @var string
     */
    protected $fileExtension;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param string|null $directory
     * @param string $fileExtension
     */
    public function __construct($directory = null, $fileExtension = 'php')
    {
        $this->directory = $directory;
        $this->fileExtension = $fileExtension;
    }

    /**
     * @param string $name
     * @param array $data
     * @return string
     */
    public function render($name, array $data = array())
    {
        return $this->process($name)->render($data);
    }

    /**
     * @param string $name
     * @return Template
     */
    public function process($name)
    {
        return new Template($this, $name);
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     * @return self
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @param string[] $paths
     * @return self
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
        return $this;
    }

    /**
     * @param string $name
     * @param string $path
     * @return self
     */
    public function setPath($name, $path)
    {
        $this->paths[$name] = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @param string $fileExtension
     * @return self
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}