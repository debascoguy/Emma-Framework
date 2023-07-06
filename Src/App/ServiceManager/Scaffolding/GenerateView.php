<?php

namespace Emma\App\ServiceManager\Scaffolding;

use Emma\Common\Utils\File;
use Emma\Di\Utils\StringManagement;

class GenerateView extends File
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
     * @param string $viewName
     * @return string
     */
    public function generate(string $viewName = "Index")
    {
        $viewName = StringManagement::underscoreToCamelCase($viewName);
        $viewPath = $this->moduleDirectory . DIRECTORY_SEPARATOR . "View" . DIRECTORY_SEPARATOR . "$viewName.php";
        $newLine = self::newLine();
        $newLineTab = self::newLineTabbed();
        $endOfLine = self::EndOfLine();

        $indexFile = fopen($viewPath, "w");
        $comment = "/**" . $this->newLineTabbed()
            . " * @var string \$html" . $this->newLineTabbed()
            . " */";
        fwrite($indexFile, "<?php" . $newLine . $comment . $endOfLine . $newLine);
        fwrite($indexFile, "echo \$html;" . $newLine);
        fwrite($indexFile, $newLine);
        fclose($indexFile);
        return $viewPath;
    }

}