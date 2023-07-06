<?php
namespace Emma\Console\CronJobs;

use Emma\Console\Command\AbstractCommand;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/19/2017
 * Time: 7:02 AM
 */
class AbstractCronJob extends AbstractCommand
{
    /**
     * @var bool
     */
    protected bool $cronJob = true;


}