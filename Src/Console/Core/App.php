<?php
namespace Emma\Console\Core;

use Emma\App\ErrorHandler\Exception\BaseException;
use Emma\Common\Utils\StringManagement;
use Emma\Console\RunInterface;
use Emma\Dbal\Connection\PDOConnection;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class App
{
    /**
     * @var array console Arguments
     */
    protected array $consoleArguments = [];

    /**
     * @var int count console arguments
     */
    protected int $numberOfArgument = 0;

    /**
     * @var array
     */
    protected array $parameterBags = [];

    /**
     * @var PDOConnection|null
     */
    protected ?PDOConnection $connection = null;

    public function __construct($argv, $argc)
    {
        $this->setConsoleArguments($argv)->setNumberOfArgument($argc);
        $this->setParameterBags(self::normalizeArgumentIntoParameterBags($argv));
    }

    /**
     * @throws BaseException
     */
    public function run()
    {
        /** HELP */
        if (empty($this->parameterBags["run"])) {
            if (StringManagement::contains($this->parameterBags, "help")
                || StringManagement::contains($this->parameterBags, "h")
            ) {
                echo $this->help();
                exit();
            }
        }

        /** @var RunInterface $run */
        $run = (isset($this->parameterBags["cronJobs"]) && $this->parameterBags["cronJobs"]) ?
            new RunCronJobs($this) : new RunCommand($this);

        $run->execute();
    }

    /**
     * @return Help
     */
    public function help(): Help
    {
        return new Help();
    }

    /**
     * @param array $consoleArguments
     * @return array
     */
    public static function normalizeArgumentIntoParameterBags(array $consoleArguments): array
    {
        $parametersBags = array();
        foreach ($consoleArguments as $params) {
            if (stripos($params, ":") !== false) {
                $bags = explode(":", $params);
                $parametersBags[StringManagement::strip_space($bags[0])] = StringManagement::strip_space($bags[1]);
            } else {
                $parametersBags[] = $params;
            }
        }
        return $parametersBags;
    }

    /**
     * @return array
     */
    public function getConsoleArguments(): array
    {
        return $this->consoleArguments;
    }

    /**
     * @param array $consoleArguments
     * @return App
     */
    public function setConsoleArguments(array $consoleArguments): static
    {
        $this->consoleArguments = $consoleArguments;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfArgument(): int
    {
        return $this->numberOfArgument;
    }

    /**
     * @param int $numberOfArgument
     * @return App
     */
    public function setNumberOfArgument(int $numberOfArgument): static
    {
        $this->numberOfArgument = $numberOfArgument;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameterBags(): array
    {
        if (empty($this->parameterBags)) {
            $this->parameterBags = self::normalizeArgumentIntoParameterBags($this->getConsoleArguments());
        }
        return $this->parameterBags;
    }

    /**
     * @param array $parameterBags
     * @return App
     */
    public function setParameterBags(array $parameterBags): static
    {
        $this->parameterBags = $parameterBags;
        return $this;
    }

    /**
     * @return PDOConnection
     */
    public function getConnection(): ?PDOConnection
    {
        return $this->connection;
    }

    /**
     * @param PDOConnection $connection
     * @return App
     */
    public function setConnection(PDOConnection $connection): static
    {
        $this->connection = $connection;
        return $this;
    }

}