<?php

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 *
 * Example:
 * HOW TO RUN FROM COMMAND LINE:
 *
 * $ php -f Emma/Console/console.php help
 * OR
 * $ php -f Emma/Console/console.php run:[Command-ID] [Optional:CommandParameters]
 * EXAMPLE:
 * ========
 * php -f Emma/Console/console.php run:test
 * $ php -f Emma/Console/console.php run:generate-module module:Index
 * OR
 * HOW TO RUN BATCH CRON-JOBS:
 * =======================
 * php -f Emma/Console/console.php cronJobs:true
 *
 */

include dirname(__DIR__, 5).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR . "autoload.php";

try {
    $console = new \Emma\Console\Core\App($argv, $argc);
    $console->run();
} catch (\Emma\App\ErrorHandler\Exception\BaseException $ex) {
    echo "Error Launching Console Application --> Error Code: " . $ex->getCode() . "\nMessage:\n\n";
    echo $ex->getMessage();
    exit();
}


