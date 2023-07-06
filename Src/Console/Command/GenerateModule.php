<?php
namespace Emma\Console\Command;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class GenerateModule extends AbstractCommand
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function run(): bool
    {
        $parametersBags = $this->getParameters();

        $GenerateModule = new \Emma\App\ServiceManager\Scaffolding\GenerateModule(
            $parametersBags["module"],
            !empty($parametersBags["controller"]) ? $parametersBags["controller"] : null,
            !empty($parametersBags["service"]) ? $parametersBags["service"] : null,
        );
        if (!empty($parametersBags["controller"]) || !empty($parametersBags["service"])) {
            $GenerateModule->setAllowOverride(false);
        }
        $GenerateModule->setGenerateController(!empty($parametersBags["controller"]));
        $GenerateModule->setGenerateService(!empty($parametersBags["service"]));
        return $GenerateModule->generate();
    }

    /**
     * @return string
     */
    public function help(): string
    {
        $message = "\nHOW TO USE EXAMPLE: \n";
        $message .= "> php -f Emma/Console/console.php run:generate-module module:[moduleName]\n";
        $message .= "EXAMPLE USING PARAMETER(S):\n";
        $message .= "> php -f Emma/Console/console.php run:generate-module module:index\n";

        return $message;
    }
}