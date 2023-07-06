<?php
namespace Emma\Console\Core;

use Emma\Console\ConsoleInterface;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/19/2017
 * Time: 9:05 AM
 */
class Help
{
    /**
     * @return string
     */
    public function help()
    {
        /** @var Container $commandContainer */
        $commandContainer = Container::getInstance();
        $allConsoleCommand = $commandContainer->getIterator();
        $help = "";
        foreach ($allConsoleCommand as $runCommand => $consoleCommand) {
            if ($consoleCommand instanceof ConsoleInterface) {
                $help .= "JOB ID: $runCommand <<==>> Command Interface: " . get_class($consoleCommand);
                $help .= "\nIs Cron Job: " . $consoleCommand->isCronJob() ? "Yes" : "No";
                $help .= "\n" . $consoleCommand->help() . "\n";
            }
        }
        return $help;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->help();
    }
}