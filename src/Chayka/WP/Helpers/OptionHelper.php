<?php

namespace Chayka\WP\Helpers;

class OptionHelper {

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
        return get_option($key, $default, !$reload);
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
        return get_site_option($key, $default, !$reload);
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
        return update_site_option($key, $value);
    }
    
}
