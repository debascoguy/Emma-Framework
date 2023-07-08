<?php

namespace Emma\App\ServiceManager\Scaffolding;

use Emma\App\ServiceManager\AbstractService;
use Emma\Common\Utils\File;
use Emma\Common\Utils\StringManagement;

class GenerateService extends File
{
    /**
     * @throws \Exception
     */
    public function __construct(protected string $moduleDirectory)
    {
        if (!is_dir($this->moduleDirectory)) {
            throw new \Exception("Error 404 : Module Not Found - Invalid Module Passed For Generate Controller!");
        }
    }

    /**
     * @param string $serviceName
     * @return string
     * @throws \Exception
     */
    public function generate(string $namespace, string $serviceName)
    {
        if (empty($serviceName)) {
            throw new \Exception("Error 404 : Service Name Not Found - InvalidService Name Passed For Generate Service!");
        }
        $namespace = StringManagement::underscoreToCamelCase($namespace, true);
        $serviceName = StringManagement::underscoreToCamelCase($serviceName, true);
        $moduleName = basename($this->moduleDirectory);
        $sampleServicePath = $this->moduleDirectory . DIRECTORY_SEPARATOR . "/Model/Services/{$serviceName}Service.php";
        $newLine = self::newLine();
        $endOfLine = self::EndOfLine();

        $sampleServiceFile = fopen($sampleServicePath, "w");
        fwrite($sampleServiceFile, "<?php" . $endOfLine . $newLine);
        fwrite($sampleServiceFile, "namespace $namespace\\Modules\\$moduleName\\Model\\Services;" .  $endOfLine . $newLine);
        fwrite($sampleServiceFile, "use " . AbstractService::class . ";" . $endOfLine . $newLine);
        fwrite($sampleServiceFile, "class {$serviceName}Service extends AbstractService " . $newLine . "{" . $endOfLine);
        fwrite($sampleServiceFile, $newLine . "}");
        fclose($sampleServiceFile);

        return $sampleServicePath;
    }
}