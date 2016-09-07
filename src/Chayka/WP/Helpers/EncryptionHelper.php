<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 07.09.16
 * Time: 12:49
 */

namespace Chayka\WP\Helpers;


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