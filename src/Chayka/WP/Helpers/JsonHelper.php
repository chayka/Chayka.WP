<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Helpers;

use Chayka\Helpers;
use WP_Error;

/**
 * Class JsonHelper wraps all the api output into an envelope
 *
 * {
 *  payload: mixed,
 *  message: string,
 *  code: string|int
 * }
 *
 * Payload is scanned recursively for JsonReady interface instances,
 * so that json representaion of the object can be customized
 *
 * This class differs from it's base class by the ability to pack WP_Errors object
 *
 * @package Chayka\Helpers
 */
class JsonHelper extends Helpers\JsonHelper {

    /**
     * Wrap multiple errors into {'payload': ..., 'code': ..., 'message': ...} envelope.
     * Set http response code to $httpResponseCode = 400.
     * And then die() it.
     *
     * @param array/WP_Error $errors
     * @param mixed $payload
     * @param int $httpResponseCode
     */
    public static function respondErrors($errors, $payload = null, $httpResponseCode = 400){
        if($errors instanceof WP_Error){
            $errors = self::packWpErrors($errors);
        }

        parent::respondErrors($errors, $payload, $httpResponseCode);
    }

    /**
     * Wrap WP_Errors object into assoc array.
     *
     * @param WP_Error $errors
     * @return array
     */
    public static function packWpErrors($errors){
        $codes = $errors->get_error_codes();
        $json = array();
        foreach ($codes as $code) {
            $json[$code] = preg_replace('%<strong>[^<]*</strong>:\s*%m', '', $errors->get_error_message($code));
        }
        return $json;
    }

}