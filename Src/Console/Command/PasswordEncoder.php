<?php

namespace Emma\Console\Command;

use Emma\Security\Services\PasswordEncoder as PasswordEncoderService;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/18/2017
 * Time: 9:18 AM
 */
class PasswordEncoder extends AbstractCommand
{
    /**
     * @return bool
     */
    public function run(): bool
    {
        $parametersBags = $this->getParameters();
        if (isset($parametersBags["text"])) {
            var_dump(PasswordEncoderService::encodePassword($parametersBags["text"]));
        }
        if (isset($parametersBags["validate"]) && $parametersBags["validate"]) {
            var_dump(PasswordEncoderService::validatePassword($parametersBags["cipher"], $parametersBags["text"]));
        }
        return true;
    }

    /**
     * @return string
     */
    public function help(): string
    {
        $message = "\nHOW TO USE EXAMPLE: \n";
        $message .= "TO ENCRYPT STRING AS PASSWORD\n
                    > php -f Emma/Console/console.php run:encrypt-password text:johnDoe\n";
        return $message;
    }
}