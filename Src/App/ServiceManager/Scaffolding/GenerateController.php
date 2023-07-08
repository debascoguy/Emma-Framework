<?php

namespace Emma\App\ServiceManager\Scaffolding;

use Emma\App\Controller\BaseController;
use Emma\App\Controller\Plugin\Render;
use Emma\Common\Utils\File;
use Emma\Common\Utils\StringManagement;
use Emma\Di\Attribute\Inject;
use Emma\Http\Mappings\GetMapping;
use Emma\Http\Mappings\PostMapping;
use Emma\Http\Mappings\RequestMapping;
use Emma\Http\Request\Method;
use Emma\Http\Request\RequestFactory;
use Emma\Http\Request\RequestInterface;
use Emma\Http\Response\ResponseInterface;

class GenerateController extends File
{
    /**
     * @throws \Exception
     */
    public function __construct(protected string $moduleDirectory)
    {
        if (!is_dir($this->moduleDirectory)) {
            throw new \Exception("Error 404 : Module Not Found - Invalid Module Passed For Generate Controller!");
        }
    }

    /**
     * @param string $controllerName
     * @return string
     */
    public function generate(string $namespace, string $controllerName)
    {
        $moduleName = basename($this->moduleDirectory);
        $namespace = StringManagement::underscoreToCamelCase($namespace, true);
        $moduleName = StringManagement::underscoreToCamelCase($moduleName, true);
        $controllerName = StringManagement::underscoreToCamelCase($controllerName, true);
        $controllersDirectory = $this->moduleDirectory . DIRECTORY_SEPARATOR . "Controller";
        self::makeDirectory($controllersDirectory);
        $newLine = self::newLine();
        $newLineTab = self::newLineTabbed();
        $endOfLine = self::EndOfLine();
        $tab = self::tab();
        $controllerNameLowerCase = strtolower($controllerName);
        $moduleNameLowerCase = strtolower($moduleName);

        $controllerPath = $controllersDirectory . DIRECTORY_SEPARATOR . "{$controllerName}Controller.php";
        $indexControllerFile = fopen($controllerPath, "w");
        fwrite($indexControllerFile, "<?php" . $endOfLine . $newLine);
        fwrite($indexControllerFile, "namespace $namespace\\Modules\\$moduleName\\Controller;" . $endOfLine . $newLine);

        fwrite($indexControllerFile, "use " . BaseController::class . "; " . $endOfLine);
        fwrite($indexControllerFile, "use " . Method::class . "; " . $endOfLine);
        fwrite($indexControllerFile, "use " . Inject::class . "; " . $endOfLine);
        fwrite($indexControllerFile, "use " . RequestFactory::class . "; " . $endOfLine);
        fwrite($indexControllerFile, "use " . RequestInterface::class . "; " . $endOfLine);
        fwrite($indexControllerFile, "use " . ResponseInterface::class . "; " . $endOfLine);
        fwrite($indexControllerFile, "use " . Render::class . "; " . $endOfLine);
        fwrite($indexControllerFile, "use " . RequestMapping::class . "; " . $endOfLine);
        fwrite($indexControllerFile, "use " . PostMapping::class . "; " . $endOfLine);
        fwrite($indexControllerFile, "use " . GetMapping::class . "; " . $endOfLine);
        fwrite($indexControllerFile, $endOfLine);

        fwrite($indexControllerFile, "#[RequestMapping(routes: '/$moduleNameLowerCase/$controllerNameLowerCase', httpRequestMethod: [Method::POST, Method::GET])]" . $endOfLine);
        fwrite($indexControllerFile, "class {$controllerName}Controller extends BaseController " . $endOfLine . "{" . $endOfLine);

        $injectRequest  = $tab . "/**" . $newLineTab . " * @var RequestInterface" . $newLineTab . " */";
        $injectRequest .= $newLineTab . "#[Inject(name: RequestFactory::class)]";
        $injectRequest .= $newLineTab . "protected ?RequestInterface \$requestFactory = null;" . $newLine;
        fwrite($indexControllerFile, $injectRequest);

        $comment = $newLineTab . "/**" . $newLineTab . " * @return Render" . $newLineTab . " */";
        $route = $newLineTab . "#[GetMapping('/index')]" . $endOfLine;
        $indexAction = $tab . "public function index() ";
        $indexAction .= $newLineTab . "{";
        $indexAction .= $newLineTab . $tab . "return \$this->render(\"index\", [\"html\" => \"Hello World! From indexAction()\"]);";
        $indexAction .= $newLineTab . "}";
        fwrite($indexControllerFile, $comment . $route . $indexAction . $endOfLine);

        $comment = $newLineTab . "/**" . $newLineTab . " * @return ResponseInterface" . $newLineTab . " */";
        $route = $newLineTab . "#[GetMapping('/json-index')]" . $endOfLine;
        $indexAction = $tab . "public function jsonSampleEndpoint() ";
        $indexAction .= $newLineTab . "{";
        $indexAction .= $newLineTab . $tab . "return \$this->json(['result' => 'Hello World! From jsonSampleEndpoint()']);";
        $indexAction .= $newLineTab . "}";
        fwrite($indexControllerFile, $comment . $route . $indexAction . $endOfLine);

        $gettersString = $newLineTab . "public function getRequestFactory(): RequestInterface";
        $gettersString .= $newLineTab . "{";
        $gettersString .= $newLineTab . $tab . "return \$this->requestFactory; ";
        $gettersString .= $newLineTab . "}";

        $settersString = $newLineTab . "public function setRequestFactory(RequestInterface \$requestFactory): static";
        $settersString .= $newLineTab . "{";
        $settersString .= $newLineTab . $tab . "\$this->requestFactory = \$requestFactory; ";
        $settersString .= $newLineTab . $tab . "return \$this; ";
        $settersString .= $newLineTab . "}";

        fwrite($indexControllerFile, $gettersString . $settersString);

        fwrite($indexControllerFile, $endOfLine . $newLine . "}" . $endOfLine);
        fclose($indexControllerFile);
        return $controllerPath;
    }

}