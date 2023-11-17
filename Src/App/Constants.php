<?php
namespace Emma\App;

use Emma\Http\Request\RequestFactory;
use Emma\Http\Response\ResponseFactory;

class Constants {

    public const CONFIG = Config::class;

    public const REQUEST = RequestFactory::class;

    public const RESPONSE = ResponseFactory::class;

    public const LANDING_PAGE = "FrontScript";

    public const ROUTES = "Routes";
}