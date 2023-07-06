<?php

use Emma\App\Controller\Plugin\{
    RequestPlugin, Url, Redirect, Render, Output, JsonResponse, RestResponse, ResponseCode, Template, CurlManager
};

use Emma\App\View\HelperPlugin\{
    Css, Js, Escape, PageHeader, PageTitle, Layout, Template as ViewTemplate
};

return [
    "controller" => [
        '_request' => RequestPlugin::class,
        'url' => Url::class,
        'redirect' => Redirect::class,
        'render' => Render::class,
        'output' => Output::class,
        'json' => JsonResponse::class,
        'restResponse' => RestResponse::class,
        'responseCode' => ResponseCode::class,
        'template' => Template::class,
        'curl' => CurlManager::class,
    ],
    "view" => [
        'css' => Css::class,
        'js' => Js::class,
        'escape' => Escape::class,
        'pageHeader' => PageHeader::class,
        'pageTitle' => PageTitle::class,
        'layout' => Layout::class,
        'template' => ViewTemplate::class,
    ]
];