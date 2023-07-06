<?php
namespace Emma\Console\Command;

use Emma\Console\ConsoleInterface;
use Emma\Dbal\Connection\PDOConnection;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/19/2017
 * Time: 4:59 AM
 */
class AbstractCommand implements ConsoleInterface
{
    /**
     * @var array
     */
    protected array $parameters = [];

    /**
     * @var PDOConnection|null
     */
    protected ?PDOConnection $connection = null;

    /**
     * @var bool
     */
    protected bool $cronJob = false;

    /**
     * @return bool
     */
    public function run(): bool
    {
        echo "Command Executed Successfully!";
        return true;
    }

    /**
     * @return string
     */
    public function help(): string
    {
        return "\nHELP: The HOW TO's \n";
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->help();
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return AbstractCommand
     */
    public function setParameters(array $parameters): static
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return PDOConnection
     */
    public function getConnection(): PDOConnection
    {
        return $this->connection;
    }

    /**
     * @param PDOConnection $connection
     * @return AbstractCommand
     */
    public function setConnection(PDOConnection $connection): static
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCronJob(): bool
    {
        return $this->cronJob;
    }

    /**
     * @param boolean $cronJob
     * @return AbstractCommand
     */
    public function setCronJob(bool $cronJob): static
    {
        $this->cronJob = $cronJob;
        return $this;
    }

}