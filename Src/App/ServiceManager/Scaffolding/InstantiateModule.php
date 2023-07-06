<?php

namespace Emma\App\ServiceManager\Scaffolding;

use Emma\Common\Utils\File;
use Emma\Common\Utils\StringManagement;

class InstantiateModule extends File
{
    protected string $baseModule;

    public function __construct(protected string $baseDirectory)
    {
        $this->baseModule = $this->baseDirectory . DIRECTORY_SEPARATOR . "Modules";
        self::makeDirectory($this->baseModule, true);
    }

    /**
     * @return bool
     */
    public function generateConfigs(): bool
    {
        return (new GenerateConfigs($this->baseDirectory))->generate();
    }

    /**
     * @param string|null $moduleName
     * @return string
     */
    public function generate(string $moduleName = null): string
    {
        if ($moduleName == null) {
            return false;
        }
        $moduleName = StringManagement::underscoreToCamelCase($moduleName, true);
        $modulePath = $this->baseModule . DIRECTORY_SEPARATOR . $moduleName;
        if (!is_dir($this->baseModule . DIRECTORY_SEPARATOR . "config")) {
            $this->generateConfigs();
        }
        self::makeDirectory($modulePath);
        self::makeDirectory($modulePath . DIRECTORY_SEPARATOR . "Controller");
//        $this->makeDirectory($modulePath . DIRECTORY_SEPARATOR . "Form");
        self::makeDirectory($modulePath . DIRECTORY_SEPARATOR . "Model");
        self::makeDirectory($modulePath . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR . "Entity");
        self::makeDirectory($modulePath . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR . "Repository");
        self::makeDirectory($modulePath . DIRECTORY_SEPARATOR . "Model" . DIRECTORY_SEPARATOR . "Services");
        self::makeDirectory($modulePath . DIRECTORY_SEPARATOR . "View");
//        $this->makeDirectory($modulePath . DIRECTORY_SEPARATOR . "Extras");
        return $modulePath;
    }

    /**
     * @return string
     */
    public function getBaseDirectory(): string
    {
        return $this->baseDirectory;
    }

    /**
     * @param string $baseDirectory
     * @return InstantiateModule
     */
    public function setBaseDirectory(string $baseDirectory): InstantiateModule
    {
        $this->baseDirectory = $baseDirectory;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseModule(): string
    {
        return $this->baseModule;
    }

    /**
     * @param string $baseModule
     * @return InstantiateModule
     */
    public function setBaseModule(string $baseModule): InstantiateModule
    {
        $this->baseModule = $baseModule;
        return $this;
    }

}