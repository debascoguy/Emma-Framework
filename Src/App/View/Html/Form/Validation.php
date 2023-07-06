<?php
namespace Emma\App\View\Html\Form;

/**
 * Class Validation
 */
class Validation
{
    const OPTIONAL = "novalidate";
    const NOVALIDATE = "novalidate";
    const REQUIRED = "validate_nonempty";
    const NON_EMPTY = "validate_nonempty";
    const NUMBERS = "validate_number";
    const PHONE = "validate_phone";
    const FAX = "validate_fax";
    const EMAIL = "validate_email";
    const PASSWORD = "validate_password";
    const CONFIRM_PASSWORD = "validate_confirm_password";
    const ZIP_CODE = "validate_zipcode";
    const AGREEMENT = "validate_agreement"; //VALIDATE AGREEMENT CHECKBOX

    public $dataValidation = array();

    /**
     * @return string
     */
    public static function VALIDATE_REQUIRED()
    {
        return self::REQUIRED;
    }

    /**
     * @return string
     */
    public static function VALIDATE_NON_EMPTY()
    {
        return self::NON_EMPTY;
    }

    /**
     * @return string
     */
    public static function VALIDATE_NUMBERS()
    {
        return self::NUMBERS;
    }

    /**
     * @return string
     */
    public static function VALIDATE_PHONE()
    {
        return self::PHONE;
    }

    /**
     * @return string
     */
    public static function VALIDATE_FAX()
    {
        return self::FAX;
    }

    /**
     * @return string
     */
    public static function VALIDATE_EMAILS()
    {
        return self::EMAIL;
    }

    /**
     * @return string
     */
    public static function VALIDATE_PASSWORD()
    {
        return self::PASSWORD;
    }

    /**
     * @return string
     */
    public static function VALIDATE_CONFIRM_PASSWORD()
    {
        return self::CONFIRM_PASSWORD;
    }

    /**
     * @return string
     */
    public static function VALIDATE_ZIP_CODE()
    {
        return self::ZIP_CODE;
    }

    /**
     * @return string
     */
    public static function NOVALIDATE()
    {
        return self::NOVALIDATE;
    }

    /**
     * @return string
     */
    public static function OPTIONAL()
    {
        return self::OPTIONAL;
    }

    /**
     * @return string
     */
    public static function AGREEMENT()
    {
        return self::AGREEMENT;
    }

    /**
     * @return array
     */
    static function getConstants()
    {
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

}