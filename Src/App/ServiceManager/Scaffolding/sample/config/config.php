<?php

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */

/**
 * NOTE:  All Variables added into the config can be accessed in the Di Container.
 */
return [
    "ini_set" => [
        "log_errors" => 1,
        //If only directory is supplied, Then, we can use the log-style File naming below.
        "error_log" => dirname(__FILE__) . DIRECTORY_SEPARATOR . "log" . DIRECTORY_SEPARATOR,
        "upload-max-filesize" => "10M",
        "post_max_size" => "10M",
    ],

    "language" => "en-US",
    "charset" => "UTF-8",
    "enableCsrfValidation" => false,
    "csrfParamName" => "csrf_token",
    
    /** logging State of Error Logging */
    "logStyle" => \Emma\App\Log::DAILY,

    "allowed_origin" => [
        "localhost:4400",
        "localhost:8100",
        "192.168.0.125:4400",
        "192.168.0.125:8100",
    ],

    "injectables" => [

    ],

    "modules_config" => [

    ],

    "controllers.registry" => (array)include __DIR__ . DIRECTORY_SEPARATOR . "controllers.registry.php",
    "database_config" => (array)include __DIR__ . DIRECTORY_SEPARATOR . "db.config.php",
    "plugins" => (array) include dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . "emma/app/plugins.php",
    "session_timeout" => 3600,
    "session_exempt" => [

    ],
    "login_attempt_rules" => [
        /** numberOfAttempts => minutes */
        5 => 1,
        10 => 60
    ],
    "document_directory" => [],
    "templates" => [

    ],
];
