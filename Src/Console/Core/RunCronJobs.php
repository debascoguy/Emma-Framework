<?php
namespace Emma\Console\Core;

use Emma\App\ErrorHandler\Exception\BaseException;
use Emma\Common\Utils\DateTimeUtils;
use Emma\Console\ConsoleInterface;
use Emma\Console\RunInterface;
use Emma\ServiceManager\Container;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/19/2017
 * Time: 9:11 AM
 */
class RunCronJobs implements RunInterface
{
    /**
     * @var App
     */
    protected $App;

    /**
     * @var int
     */
    protected static $countExecutedJobs = 0;

    /**
     * @param App $App
     */
    public function __construct(App $App)
    {
        $this->setApp($App);
    }

    /**
     * @param array $executed
     * @return bool
     */
    public function execute($executed = array())
    {
        /** @var Container $commandProperty */
        $commandProperty = Container::getInstance();
        $iterator = $commandProperty->getIterator();
        try {
            foreach ($iterator as $jobId => $service) {
                if (isset($executed[$jobId]) && $executed[$jobId] == $service) {
                    /**
                     * If Exception Occurred and this function is being called recursively,
                     * Jump All already executed Jobs. This also includes the Job throwing Exception.
                     */
                    continue;
                }

                //Register Currently Executing Job
                $executed[$jobId] = $service;

                if ($service instanceof ConsoleInterface) {
                    if ($service->isCronJob()) {
                        $service->setConnection($this->getApp()->getConnection());
                        $service->setParameters($this->getApp()->getParameterBags());
                        $service->run();
                        $jobCount = self::$countExecutedJobs++;
                        error_log("Job Run Successfully! ($jobCount) - Running JobId: $jobId <<==>> Job Name: " . get_class($service));
                    }
                } else {
                    throw new BaseException(
                        "Cron Job MUST implement CronJobInterface", 501
                    );
                }
            }
        } catch (BaseException $e) {
            $this->exceptionHandler($e, "\nCron Job Failed! - Report Timestamp: " . DateTimeUtils::getDateTime());
            $this->execute($executed);  //Recursively Jump to the Next Cron JOB
        }

        return (self::$countExecutedJobs > 0);
    }

    /**
     * @param BaseException $e
     * @param string $extra
     * @return bool
     */
    public function exceptionHandler(BaseException $e, $extra = "")
    {
        error_log("Cron Error In File: " . $e->getFile()
            . "\nAt Line: " . $e->getLine() . "\nError Message: " . $e->getMessage() . $extra);
        return true;
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