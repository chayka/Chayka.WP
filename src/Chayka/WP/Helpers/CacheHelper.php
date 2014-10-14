<?php

namespace Chayka\WP\Helpers;

use \Closure;

class CacheHelper {

    /**
     * Returns cached value if one exists or default one.
     * Callback or Closure can be passed as $default like this:
     * 
     * $a = 'John'
     * 
     * $value = CacheHelper::_('some cache', DAY_IN_SECONDS, function($key) use ($a){
     *      return "Hello $a";
     * });
     * 
     * @param string $key
     * @param integer $expiration
     * @param callable|Closure $default
     * @return mixed
     */
    public static function _($key, $expiration, $default){
        return self::getValue($key, $default, $expiration);
    }
    
    
    /**
     * Returns cached value if one exists or default one.
     * Callback or Closure can be passed as $default like this:
     * 
     * $a = 'John'
     * 
     * $value = CacheHelper::getValue('some cache', function($key) use ($a){
     *      return "Hello $a";
     * }, DAY_IN_SECONDS);
     * 
     * @param string $key
     * @param string|callable|Closure $default
     * @param integer $expiration
     * @return mixed
     */
    public static function getValue($key, $default='', $expiration = 0){
        $value = wp_cache_get($key, '');
        if(!$value){
            $value = get_transient($key);
            if(!$value && $default){
                if($default instanceof \Closure){
                    $value = $default($key);
                }else if(is_callable($default)){
                    $value = call_user_func($default, $key);
                }else{
                    $value = $default;
                }
                $value && self::setValue($key, $value, $expiration);
            }
            $value && wp_cache_set($key, $value, '', $expiration);
        }
        return $value;
    }
    
    /**
     * Store value in cache
     * 
     * @param string $key
     * @param mixed $value
     * @param integer $expiration
     * @return bool
     */
    public static function setValue($key, $value, $expiration = 0){
        wp_cache_set($key, $value, '', $expiration);
        return set_transient($key, $value, $expiration);
    }
    
    /**
     * Delete stored value
     * 
     * @param string $key
     * @return bool
     */
    public static function deleteValue($key){
        wp_cache_delete($key);
        return delete_transient($key);
    }
    
    /**
     * Flush stored value from memory
     * 
     * @param string $key
     * @return bool
     */
    public static function flushValue($key){
        return wp_cache_delete($key);
    }

    /* Multisite global functions */
    
    /**
     * Returns cached value if one exists or default one.
     * Callback or Closure can be passed as $default like this:
     * 
     * $a = 'John'
     * 
     * $value = CacheHelper::_('some cache', DAY_IN_SECONDS, function($key) use ($a){
     *      return "Hello $a";
     * });
     * 
     * @param string $key
     * @param integer $expiration
     * @param callable|Closure $default
     * @return mixed
     */
    public static function __($key, $expiration, $default){
        return self::getSiteValue($key, $default, $expiration);
    }
    
    
    /**
     * Returns cached value if one exists or default one.
     * Callback or Closure can be passed as $default like this:
     * 
     * $a = 'John'
     * 
     * $value = CacheHelper::getValue('some cache', function($key) use ($a){
     *      return "Hello $a";
     * }, DAY_IN_SECONDS);
     * 
     * @param string $key
     * @param string|callable|Closure $default
     * @param integer $expiration
     * @return mixed
     */
    public static function getSiteValue($key, $default='', $expiration = 0){
        $value = wp_cache_get($key, 'site');
        if(!$value){
            $value = get_site_transient($key);
            if(!$value && $default){
                if($default instanceof \Closure){
                    $value = $default($key);
                }else if(is_callable($default)){
                    $value = call_user_func($default, $key);
                }else{
                    $value = $default;
                }
                $value && self::setSiteValue($key, $value, $expiration);
            }
            $value && wp_cache_set($key, $value, 'site', $expiration);
        }
        return $value;
    }
    
    /**
     * Store value in cache
     * 
     * @param string $key
     * @param mixed $value
     * @param integer $expiration
     * @return bool
     */
    public static function setSiteValue($key, $value, $expiration = 0){
        wp_cache_set($key, $value, 'site', $expiration);
        return set_site_transient($key, $value, $expiration);
    }
    
    /**
     * Delete stored value
     * 
     * @param string $key
     * @return bool
     */
    public static function deleteSiteValue($key){
        wp_cache_delete($key, 'site');
        return delete_site_transient($key);
    }
    
    /**
     * Flush stored value from memory
     * 
     * @param string $key
     * @return bool
     */
    public static function flushSiteValue($key){
        return wp_cache_delete($key, 'site');
    }
    
}


