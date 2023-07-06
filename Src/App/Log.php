<?php
/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
namespace Emma\App;

use \Emma\Common\Utils\StringManagement;

class Log
{
    public const DAILY = "_daily_";

    public const MONTHLY = "_monthly_";

    public const YEARLY = "_yearly_";

    public const ALL_TOGETHER = "_all_together_";

    /**
     * @param $logStyle
     * @param null $dir
     * @return string
     */
    public static function getLogFileName($logStyle, $dir = null): string
    {
        $name = "ErrorLog";
        $extension = "log";
        if (!empty($dir) && is_dir($dir)) {
            $fullPath = StringManagement::endsWith($dir, DIRECTORY_SEPARATOR) ? $dir.$name : $dir.DIRECTORY_SEPARATOR.$name;
        }
        else {
            $fullPath = $name;
        }
        return match ($logStyle) {
            self::DAILY => $fullPath . self::DAILY . date("Y_m_d") . "." . $extension,
            self::MONTHLY => $fullPath . self::MONTHLY . date("Y_m") . "." . $extension,
            self::YEARLY => $fullPath . self::YEARLY . date("Y") . "." . $extension,
            default => $fullPath . self::ALL_TOGETHER . "." . $extension,
        };
    }


}