<?php

namespace Emma\App\ServiceManager\Scaffolding;

use Emma\App\Config;
use Emma\Common\Utils\File;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class GenerateModule extends File {

    /**
     * @var string
     */
    protected string $modulesDirectory = "";

    /**
     * @var string
     */
    protected string $newModuleDirectory = "";

    /**
     * @var bool 
     */
    protected bool $generateController = false;

    /**
     * @var bool
     */
    protected bool $generateService = false;

    /**
     * This is used to determine if the generator can override existing module and/or controller files.
     * @var bool
     */
    protected bool $allowOverride = true;

    protected ?InstantiateModule $generateModules = null;

    /**
     * @param string $moduleName
     * @param string $controllerName
     * @param string $serviceName
     */
    public function __construct(
        protected string $namespace = "MyFirstApp",
        protected string $moduleName = "index",
        protected ?string $controllerName = "index",
        protected ?string $serviceName = "index"
    ) {
        $this->generateModules = new InstantiateModule(Config::getFrameworkBaseRoute());
        $this->setNewModuleDirectory($this->generateModules->generate($moduleName));
        $this->modulesDirectory = $this->generateModules->getBaseModule();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function generate()
    {
        $modulePath = $this->getNewModuleDirectory();

        if ($this->isGenerateController()) {
            if (!is_dir($modulePath)) {
                echo "Invalid Module Name - $modulePath - Module Does Not Exist!";
                return false;
            }

            if (is_null($this->controllerName)) {
                echo "Controller Name - Cannot Be NULL!";
                return false;
            }

            $controllerPath = $modulePath . DIRECTORY_SEPARATOR . "Controller" . DIRECTORY_SEPARATOR . $this->controllerName."Controller.php";
            if (!$this->isAllowOverride() && file_exists($controllerPath)) {
                echo "Controller Exist and Override is Not Allowed!";
                return false;
            }

            echo "\nController File: " . $this->generateController();
            echo "\nController View: " . $this->generateView();
        }

        if ($this->isGenerateService()) {
            if (!is_dir($modulePath)) {
                echo "Invalid Module Name - $modulePath - Module Does Not Exist!";
                return false;
            }

            if (is_null($this->serviceName)) {
                echo "Service Name - Cannot Be NULL!";
                return false;
            }

            $servicePath = $modulePath
                . DIRECTORY_SEPARATOR . "Model"
                . DIRECTORY_SEPARATOR."Services"
                . DIRECTORY_SEPARATOR . $this->serviceName."Service.php";
            if (!$this->isAllowOverride() && file_exists($servicePath)) {
                echo "Service Exist and Override is Not Allowed!";
                return false;
            }
            echo "\nService File: " . $this->generateModelService();
        }

        return true;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateModelService(): string
    {
        return (new GenerateService($this->newModuleDirectory))->generate($this->namespace, $this->serviceName);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateController(): string
    {
        return (new GenerateController($this->newModuleDirectory))->generate($this->namespace, $this->controllerName);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateView(): string
    {
        return (new GenerateView($this->newModuleDirectory))->generate($this->controllerName);
    }

    /**
     * @return string
     */
    public function getModulesDirectory(): string
    {
        return $this->modulesDirectory;
    }

    /**
     * @param string $modulesDirectory
     * @return GenerateModule
     */
    public function setModulesDirectory(string $modulesDirectory): GenerateModule
    {
        $this->modulesDirectory = $modulesDirectory;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewModuleDirectory(): string
    {
        return $this->newModuleDirectory;
    }

    /**
     * @param string $newModuleDirectory
     * @return GenerateModule
     */
    public function setNewModuleDirectory(string $newModuleDirectory): GenerateModule
    {
        $this->newModuleDirectory = $newModuleDirectory;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGenerateController(): bool
    {
        return $this->generateController;
    }

    /**
     * @param bool $generateController
     * @return GenerateModule
     */
    public function setGenerateController(bool $generateController): GenerateModule
    {
        $this->generateController = $generateController;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGenerateService(): bool
    {
        return $this->generateService;
    }

    /**
     * @param bool $generateService
     * @return GenerateModule
     */
    public function setGenerateService(bool $generateService): GenerateModule
    {
        $this->generateService = $generateService;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowOverride(): bool
    {
        return $this->allowOverride;
    }

    /**
     * @param bool $allowOverride
     * @return GenerateModule
     */
    public function setAllowOverride(bool $allowOverride): GenerateModule
    {
        $this->allowOverride = $allowOverride;
        return $this;
    }

}
