<?php

namespace Emma\App\ServiceManager\Scaffolding;

use Emma\Common\Utils\File;

class GenerateConfigs extends File
{
    public function __construct(protected string $baseDirectory)
    {
    }

    /**
     * @return bool
     */
    public function generate(): bool
    {
        $sampleConfigDirectory = __DIR__ . DIRECTORY_SEPARATOR . "sample" .DIRECTORY_SEPARATOR . "config";
        $configDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR . "config";
        if (is_dir($configDirectory) && file_exists($configDirectory . DIRECTORY_SEPARATOR . "config.php")) {
            return true;
        }
        self::makeDirectory($configDirectory, true);
        $this->copy($sampleConfigDirectory, $configDirectory);
        return true;
    }

}