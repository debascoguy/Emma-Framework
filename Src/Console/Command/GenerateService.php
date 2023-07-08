<?php
namespace Emma\Console\Command;
/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class GenerateService extends AbstractCommand
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function run(): bool
    {
        $parametersBags = $this->getParameters();
        $GenerateModule = new \Emma\App\ServiceManager\Scaffolding\GenerateModule(
            $parametersBags["namespace"],
            $parametersBags["module"], 
            $parametersBags["controller"] ?? null, 
            $parametersBags["service"] ?? null
        );
        return $GenerateModule->setGenerateService(true)->generate();
    }

    /**
     * @return string
     */
    public function help(): string
    {
        $message = "\nHOW TO USE EXAMPLE: \n";
        $message .= "> php -f vendor/emma/framework/Src/Console/console.php run:generate-service module:[moduleName] namespace:[namespace] service:[serviceName]\n";
        $message .= "EXAMPLE USING PARAMETER(S):\n";
        $message .= "> php -f vendor/emma/framework/Src/Console/console.php run:generate-module module:index service:Api\n";

        return $message;
    }
}