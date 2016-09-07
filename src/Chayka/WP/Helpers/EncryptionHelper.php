<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Helpers;

/**
 * Class EncryptionHelper provides methods to encrypt and decrypt data.
 *
 * @package Chayka\Helpers
 */
class EncryptionHelper extends \Chayka\Helpers\EncryptionHelper{

    /**
     * Encrypt provided data.
     *
     * @param string $value
     * @param string $key
     *
     * @param string $cipher
     * @param string $mode
     *
     * @return string
     */
    public static function encrypt($value, $key = '', $cipher = '', $mode = ''){
        if(!$key && defined('NONCE_KEY')){
            $key = NONCE_KEY;
        }
        return parent::encrypt($value, $key, $cipher, $mode);
    }

    /**
     * Decrypt provided data.
     * If decryption failed, returns initial data.
     *
     * @param string $value
     * @param string $key
     *
     * @param string $cipher
     * @param string $mode
     *
     * @return string
     */
    public static function decrypt($value, $key = '', $cipher = '', $mode = ''){
        if(!$key && defined('NONCE_KEY')){
            $key = NONCE_KEY;
        }
        return parent::decrypt($value, $key, $cipher, $mode);
    }
}