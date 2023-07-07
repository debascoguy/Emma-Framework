<?php
namespace Emma\App;

use Emma\Countries\Singleton;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class Config
{
    use Singleton;

    protected string $frontScript = "";
    
    public string $language = "en-US";
    
    public string $charset = "UTF-8";

    /**
     * @var bool [determine if every form / 'POST' request within the framework should use csrf token]
     */
    public bool $enableCsrfValidation;
    public string $csrfParamName;

    /**
     * @var \ArrayIterator|null
     */
    public ?\ArrayIterator $appConfig;


    /**
     * Config constructor.
     */
    public function __construct()
    {
        /** @var \ArrayIterator $this->appConfig */
        $this->appConfig = self::loadConfig();
        $this->enableCsrfValidation = $this->appConfig["enableCsrfValidation"] ?? false;
        $this->csrfParamName = $this->appConfig["csrfParamName"] ?? "csrf_token";
        $this->language = $this->appConfig["language"] ?? "en-US";
        $this->charset = $this->appConfig["charset"] ?? "UTF-8";
    }
    
    /**
     * @return string
     */
    public function getFrontScript(): string
    {
        return $this->frontScript;
    }

    /**
     * @param string $frontScript
     * @return self
     */
    public function setFrontScript(string $frontScript = ""): static
    {
        if (empty($frontScript)) {
            $temp = explode("/", $_SERVER["PHP_SELF"]);
            foreach ($temp as $path) {
                if (stripos($path, ".php") !== false) {
                    $this->frontScript = $path;
                    break;
                }
            }
        } else {
            $this->frontScript = $frontScript;
        }
        return $this;
    }

    /**
     * @return string
     */
    public static function getFrameworkBaseRoute(): string
    {
        return dirname(__DIR__, 5);
    }

    /**
     * @return string
     */
    public static function getModulesRoute(): string
    {
        return self::getFrameworkBaseRoute() . DIRECTORY_SEPARATOR . "Modules";
    }

    /**
     * @return \ArrayIterator
     */
    private static function loadConfig(): \ArrayIterator
    {
        return new \ArrayIterator((array)
            include self::getFrameworkBaseRoute().DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."config.php"
        );
    }

    /**
     * @return \ArrayIterator
     */
    public static function getDatabaseConfig(): \ArrayIterator
    {
        $instance = self::getInstance();
        return new \ArrayIterator($instance->appConfig["database_config"] ?? []);
    }
}