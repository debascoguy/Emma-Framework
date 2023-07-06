<?php

namespace Emma\Console;

use Emma\App\ErrorHandler\Exception\BaseException;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 * Date: 12/19/2017
 * Time: 3:41 PM
 */
interface RunInterface
{
    /**
     * @return bool
     * @throws BaseException
     */
    public function execute();

    /**
     * @return \Emma\Console\Core\App
     */
    public function getApp();

    /**
     * @param \Emma\Console\Core\App $App
     * @return \Emma\Console\Core\RunCommand
     */
    public function setApp($App);

}