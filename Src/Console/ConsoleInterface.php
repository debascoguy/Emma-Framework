<?php

namespace Emma\Console;

use Emma\Console\Command\AbstractCommand;
use Emma\Dbal\Connection\PDOConnection;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/18/2017
 * Time: 9:11 AM
 */
interface ConsoleInterface
{
    /**
     * @return bool
     */
    public function run(): bool;

    /**
     * @return string
     */
    public function help(): string;

    /**
     * @return string
     */
    public function __toString();

    /**
     * @return array
     */
    public function getParameters(): array;

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): static;

    /**
     * @return PDOConnection
     */
    public function getConnection(): PDOConnection;

    /**
     * @param PDOConnection $connection
     */
    public function setConnection(PDOConnection $connection);

    /**
     * @return boolean
     */
    public function isCronJob(): bool;

    /**
     * @param boolean $cronJob
     * @return AbstractCommand
     */
    public function setCronJob(bool $cronJob): static;

}