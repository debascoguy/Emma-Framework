<?php
namespace Emma\App\Controller;

use Emma\App\Config;
use Emma\App\Constants;
use Emma\App\ErrorHandler\Exception\BaseException;
use Emma\App\Log;
use Emma\App\ServiceManager\Token;
use Emma\Http\HttpStatus;
use Emma\Http\Response\ResponseInterface;
use Emma\Http\Router\Route\Route;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class RestController extends BaseController
{
    /**
     * Container: args
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    protected array $args = [];

    /**
     * Container: file
     * Stores the input of the PUT request
     */
    protected ?string $file = null;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $allowedOrigins = Config::getInstance()->appConfig["allowed_origin"];
        $requestOrigin = $this->getRequest()->getServer()->get("HTTP_ORIGIN", "localhost:4400");
        $checkOrigin = str_replace(['https://', 'http://'], '', $requestOrigin);
        $http_origin = in_array($checkOrigin, $allowedOrigins) ? $requestOrigin : "localhost:4400";
        $header = [
            "Access-Control-Allow-Origin" => $http_origin,
            "Access-Control-Allow-Methods" => "GET, POST, PATCH, PUT, DELETE, OPTIONS",
            "Access-Control-Allow-Headers" => "Origin, Content-Type, X-Auth-Token, Authorization, Cache-Control, X-Requested-With"
        ];
        foreach($header as $key => $value) {
            $this->request->setHeader($key, $value);
        }
    }

    /**
     * @return bool
     */
    public function authorization(): bool
    {
        return parent::authorization();
    }

    /**
     * @return $this
     * @throws \Exception
     * GET|OPTIONS - Used for basic read requests from the server
     * PUT - Used to modify an existing object on the server
     * POST|OPTIONS - Used to create a new object on the server
     * DELETE - Used to remove an object on the server
     */
    public function normalizeArgs(): static
    {
        if (!empty($this->args)) {
            return $this;
        }
        
        $request = $this->getRequest();
        switch ($request->getServer()->getRequestMethod()) {
            case 'DELETE':
            case 'POST':
            case 'OPTIONS':
                $this->setArgs(array_filter(array_merge(
                    $request->getPost()->getParameters(),
                    $request->getQuery()->getParameters()
                )));
                break;
            case 'GET':
                $this->setArgs(array_filter($request->getQuery()->getParameters()));
                break;
            case 'PUT':
                $this->setArgs(array_filter($request->getQuery()->getParameters()));
                $this->setFile(array_filter(json_decode($request->getContent(), true)));
                break;
            default:
                throw new BaseException('Invalid Method', HttpStatus::HTTP_METHOD_NOT_ALLOWED);
        }
        return $this;
    }


    /**
     * @return ResponseInterface
     * TODO: Implement this method better with authentication token that will possibly be used with API request.
     */
    public function createSession(): ResponseInterface
    {
        $csrfSession = Token::generateCookie();
        return $this->restResponse(["session"=>$csrfSession]);
    }

    /**
     * @param int $format
     * @return array|string
     * @throws \Exception
     */
    public function createLog(int $format = 0): array|string
    {
        /** @var  Route $routes */
        $routes = $this->getContainer()->get(Constants::ROUTES);
        $server = $this->getRequest()->getServer();
        $log = [
            "function" => json_encode($routes->getCallable()),
            "args" => json_encode($this->normalizeArgs()->getArgs()),
            "request_method" => $server->getRequestMethod(),
            "origin" => $server->get("HTTP_ORIGIN"),
            "ip_address" => $server->getClientIpAddress(),
            "result_type" => "json",
            "timestamp" => date('Y-m-d H:i:s')
        ];
        if ($format == 1) {
            $result = "";
            foreach ($log as $name => $value) {
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $result .= $name . ": " . $value . "\n";
            }
            return "\n" . $result . "\n";
        }
        return $log;
    }

    /**
     * @param string $directory_path
     * @return string $directory_path
     */
    public function getLogFile(string $directory_path = ""): string
    {
        if (empty($directory_path)) {
            $configs = $this->getContainer()->get(Constants::CONFIG);
            $modulesConfig = $configs->appConfig;
            $iniSet = $modulesConfig["ini_set"];
            if (is_dir($iniSet["error_log"])) {
                $directory_path = Log::getLogFileName($modulesConfig["logStyle"], $iniSet["error_log"]);
            }
        }
        return str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $directory_path);
    }

    /**
     * @param string $directory_path
     * @return false|int
     * @throws \Exception
     */
    public function saveLogFile(string $directory_path = ""): false|int
    {
        return $this->logToFile($this->createLog(1), $directory_path);
    }

    /**
     * @param string $context
     * @param string $directory_path
     * @return false|int
     */
    public function logToFile(string $context, string $directory_path = ""): false|int
    {
        $logFile = $this->getLogFile($directory_path);
        return file_put_contents($logFile, "\n".$context, FILE_APPEND);
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @param $args
     * @return $this
     */
    public function setArgs($args): static
    {
        $this->args = $args;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setFile(string $file): static
    {
        $this->file = $file;
        return $this;
    }
}