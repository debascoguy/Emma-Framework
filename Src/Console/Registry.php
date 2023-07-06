<?php
/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */

return array(
    'test' => '\Emma\Console\Command\AbstractCommand',
//    'dao-generator' => '\Emma\Console\Command\DaoGenerator',
//    'export-database' => '\Emma\Console\Command\ExportDatabase',
    'generate-module' => \Emma\Console\Command\GenerateModule::class,
    'generate-controller' => \Emma\Console\Command\GenerateController::class,
    'generate-service' => \Emma\Console\Command\GenerateService::class,
    'encrypt-password' => \Emma\Console\Command\PasswordEncoder::class,
);
