<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Helpers;

/**
 * Class OptionHelper is a wrapper for:
 * - get_option
 * - update_option
 * - get_site_option
 * - update_site_option
 *
 * As a bonus it stores all options with suffix provided by getSuffix()
 *
 * You can create your own OptionHelper by extending this one.
 * By default getSuffix() creates suffix based on class namespace.
 * So you can just extend OptionHelper with different namespace
 * and empty class, to get custom prefix.
 *
 * Another bonus is that all options are cached.
 *
 * @package Chayka\WP\Helpers
 */
class OptionHelper {

    /**
     * Options cache, not to load them twice.
     *
     * @var array
     */
    protected static $cache = array();

    /**
     * You can ascend from this class.
     * You may want to override this class to set custom prefix.
     *
     * @return string
     */
    public static function getPrefix(){
        $obj = new static();
        $cls = get_class($obj);
        $prefix = str_replace(array('OptionHelper', '\\Helpers', '\\'), array('', '', '.'), $cls);
        return $prefix?$prefix:'';
    }

    /**
     * Alias to get_option but with custom prefix
     *
     * @param string $option
     * @param string $default
     * @param bool $reload
     * @return mixed|void
     */
    public static function getOption($option, $default='', $reload = false){
        $key = static::getPrefix().$option;
        if(!isset(self::$cache[$key]) || $reload){
            $value = get_option($key, $default);
            self::$cache[$key] = $value;
        }
        return self::$cache[$key];
    }

    /**
     * Alias to update_option but with custom prefix
     *
     * @param string $option
     * @param $value
     * @return bool
     */
    public static function setOption($option, $value){
        $key = static::getPrefix().$option;
        self::$cache[$key] = $value;
        return update_option($key, $value);
    }

    /**
     * Alias to get_site_option but with custom prefix
     *
     * @param $option
     * @param string $default
     * @param bool $reload
     * @return mixed
     */
    public static function getSiteOption($option, $default='', $reload = false){
        $key = static::getPrefix().$option;
        if(!isset(self::$cache['site_'.$key]) || $reload){
            $value = get_site_option($key, $default);
            self::$cache['site_'.$key] = $value;
        }
        return self::$cache['site_'.$key];
    }

    /**
     * Alias to get_site_option but with custom prefix
     *
     * @param $option
     * @param $value
     * @return bool
     */
    public static function setSiteOption($option, $value){
        $key = static::getPrefix().$option;
        self::$cache['site_'.$key] = $value;
        return update_site_option($key, $value);
    }

    /**
     * Encrypt provided data.
     * Encrypts with NONCE_KEY constant as a key by default
     *
     * @param $value
     * @param string $key
     *
     * @return string
     */
    public static function encrypt($value, $key = ''){
        if(!$key && defined('NONCE_KEY')){
            $key = NONCE_KEY;
        }
        if(!$key){
            $key = 'Chayka.Framework';
        }
        $key = substr(str_pad($key, 32, $key), 0, 32);
        if(function_exists('mcrypt_encrypt')){
            $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
            $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
            $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_CBC, $iv);
            $value = base64_encode($iv.$encrypted);
        }
        return $value;
    }

    /**
     * Decrypt provided data.
     * Decrypts with NONCE_KEY constant as a key by default.
     * If decryption failed, returns initial data.
     *
     * @param $value
     * @param string $key
     *
     * @return string
     */
    public static function decrypt($value, $key = ''){
        if(!$key && defined('NONCE_KEY')){
            $key = NONCE_KEY;
        }
        if(!$key){
            $key = 'Chayka.Framework';
        }
        $key = substr(str_pad($key, 32, $key), 0, 32);
        if(function_exists('mcrypt_decrypt') && preg_match('/^[\w\d\+\/]+==$/', $value)){
            $ivSize = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
            $decoded = base64_decode($value);
            $iv = substr($decoded, 0, $ivSize);
            $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, substr($decoded, $ivSize), MCRYPT_MODE_CBC, $iv);
            if($decrypted!==false){
                $value = preg_replace('/\x00*$/', '', $decrypted);
            }
        }
        return $value;
    }

    /**
     * Get previously encrypted and stored option (with custom prefix)
     *
     * @param string $option
     * @param string $default
     * @param bool $reload
     * @return mixed|void
     */
    public static function getEncryptedOption($option, $default='', $reload = false){
        $key = static::getPrefix().$option;
        if(!isset(self::$cache[$key]) || $reload){
            $value = get_option($key, $default);
            $value = self::decrypt($value);
            self::$cache[$key] = $value;
        }
        return self::$cache[$key];
    }

    /**
     * Encrypt and store option
     *
     * @param string $option
     * @param $value
     * @return bool
     */
    public static function setEncryptedOption($option, $value){
        $key = static::getPrefix().$option;
        self::$cache[$key] = $value;
        $value = self::encrypt($value);
        return update_option($key, $value);
    }

    /**
     * Get previously encrypted and stored site option (with custom prefix)
     *
     * @param $option
     * @param string $default
     * @param bool $reload
     * @return mixed
     */
    public static function getEncryptedSiteOption($option, $default='', $reload = false){
        $key = static::getPrefix().$option;
        if(!isset(self::$cache['site_'.$key]) || $reload){
            $value = get_site_option($key, $default);
            $value = self::decrypt($value);
            self::$cache['site_'.$key] = $value;
        }
        return self::$cache['site_'.$key];
    }

    /**
     * Encrypt and store site option (with custom prefix)
     *
     * @param $option
     * @param $value
     * @return bool
     */
    public static function setEncryptedSiteOption($option, $value){
        $key = static::getPrefix().$option;
        self::$cache['site_'.$key] = $value;
        $value = self::encrypt($value);
        return update_site_option($key, $value);
    }
}
