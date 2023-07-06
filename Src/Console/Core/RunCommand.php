<?php
namespace Emma\Console\Core;

use Emma\App\ErrorHandler\Exception\BaseException;
use Emma\Common\Utils\StringManagement;
use Emma\Console\RunInterface;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/19/2017
 * Time: 9:11 AM
 */
class RunCommand implements RunInterface
{
    /**
     * @var App
     */
    protected $App;

    /**
     * @param App $App
     */
    public function __construct(App $App)
    {
        $this->setApp($App);
    }

    /**
     * @return bool
     * @throws BaseException
     */
    public function execute()
    {
        $commandContainer = new \Emma\Console\Core\Container();
        $parameterBags = $this->getApp()->getParameterBags();

        try {
            $consoleCommand = $commandContainer->get($parameterBags["run"]);
            if (StringManagement::contains($parameterBags, "-h")
                || StringManagement::contains($parameterBags, "-help")
            ) {
                echo $consoleCommand->help();
                exit();
            }
            $consoleCommand->setConnection($this->getApp()->getConnection());
            $consoleCommand->setParameters($parameterBags);
            $consoleCommand->run();
            return true;
        } catch (BaseException $e) {
            echo $e->getCode() . ": " . $e->getMessage() . "\n".$e->getFile()." At Line: ".$e->getLine()."\n";
            if ($commandContainer->has($parameterBags["run"]) && !in_array($e->getCode(), array(404, 501))) {
                echo $commandContainer->get($parameterBags["run"])->help();
            }
            return false;
        }
    }

    /**
     * @return App
     */
    public function getApp()
    {
        return $this->App;
    }

    /**
     * @param App $App
     * @return RunCommand
     */
    public function setApp($App)
    {
        $this->App = $App;
        return $this;
    }
}