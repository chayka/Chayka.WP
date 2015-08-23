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
            self::$cache[$key] = get_option($key, $default);
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
            self::$cache['site_'.$key] = get_site_option($key, $default, !$reload);
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
    
}
