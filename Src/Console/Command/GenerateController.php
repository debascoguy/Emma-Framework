<?php
namespace Emma\Console\Command;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class GenerateController extends AbstractCommand
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function run(): bool
    {
        $parametersBags = $this->getParameters();
        $GenerateModule = new \Emma\App\ServiceManager\Scaffolding\GenerateModule(
            $parametersBags["module"], $parametersBags["controller"], $parametersBags["service"] ?? null
        );
        return $GenerateModule->setGenerateController(true)->generate();
    }

    /**
     * @return string
     */
    public function help(): string
    {
        $message = "\nHOW TO USE EXAMPLE: \n";
        $message .= "> php -f Emma/Console/console.php run:generate-module module:[moduleName] controller:[controllerName]\n";
        $message .= "EXAMPLE USING PARAMETER(S):\n";
        $message .= "> php -f Emma/Console/console.php run:generate-module module:index controller:Index\n";

        return $message;
    }
}