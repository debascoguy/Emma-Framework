<?php

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/18/2017
 * Time: 8:22 AM
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
 * $ php -f Emma/Console/console.php run:dao-generator bundle:Admin tablename:login_session
 * $ php -f Emma/Console/console.php run:generate-module module:Index
 * OR
 * HOW TO RUN BATCH CRON-JOBS:
 * =======================
 * php -f Emma/Console/console.php cronJobs:true
 *
 */

use Emma\Dbal\Connection\ConnectionProperty;
use Emma\Dbal\Connection\PDOConnection;

include dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR . "autoload.php";

/**
 * - Change this anytime you know the connection container parameters changed.
 */
$connectionProperty = ConnectionProperty::create([
    "host" => "localhost",
    "user" => "root",
    "password" => "",
    "dbname" => "moneypally",
    "port" => 3306,
    "driver" => \Emma\Dbal\Connection\Drivers::MYSQL,
    "dsn" => "{dbms}:host={host};port={port};dbname={db}",
]);

try {
    $connection = new PDOConnection();
    $connection->connect($connectionProperty);

    $console = new \Emma\Console\Core\App($argv, $argc);
    if ($connection->getConnection() instanceof PDO) {
        $console->setConnection($connection);
    }
    $console->run();
} catch (\Emma\App\ErrorHandler\Exception\BaseException $ex) {
    echo "Error Launching Console Application --> Error Code: " . $ex->getCode() . "\nMessage:\n\n";
    echo $ex->getMessage();
    exit();
}


