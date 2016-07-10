<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Helpers;

use Chayka\Helpers\Util;

/**
 * Class ResourceHelper is a comprehensive solution to manage your styles and scripts.
 * Basically it is a wrapper for all those
 * - wp_register_script
 * - wp_deregister_script
 * - wp_enqueue_script
 * - wp_register_style
 * - wp_enqueue_style
 * - wp_deregister_style
 *
 * However this helper loads them carefully at the appropriate hooks.
 *
 * One more thing - this helper enables usage of minimized & combined (by GruntJS|GulpJS) scripts & styles.
 * Do it like that:
 *      ResourceHelper::registerScript('jquery', 'js/jquery.js', ...);
 *      ResourceHelper::registerScript('angular', 'js/angular.js',...);
 *
 *      ResourceHelper::registerMinimizedScript('jquery-angular', 'js/jquery-angular.min.js', ['jquery', 'angular']);
 *
 * then at some point call:
 *
 *      ResourceHelper::enqueueScript('jquery');
 *
 * it will load 'js/jquery-angular.min.js'
 *
 * the next call:
 *
 *      ResourceHelper::enqueueScript('angular');
 *
 * won't load a thing because it's already loaded. Amazing!
 *
 * @package Chayka\WP\Helpers
 */
class ResourceHelper {

    /**
     * This variable stores flag whether helper should look for minimized resources
     * if available.
     *
     * @var bool
     */
    protected static $isMediaMinimized = false;

    /**
     * Mapping of resource handles to the combined styles
     *
     * @var array
     */
    protected static $combinedStyles = array();

    /**
     * Mapping of resource handles to the combined scripts
     *
     * @var array
     */
    protected static $combinedScripts = array();

    /**
     * Mapping of plugins and themes resource folders
     *
     * @var array
     */
    protected static $applicationResourceFolderUrls = array();

    /**
     * This counter is responsible for the right order of the callbacks called on hooks
     *
     * @var int
     */
    public static $callbackCounter = 0;

    /**
     * Check if we are working in the minimized media mode
     *
     * @return boolean
     */
    public static function isMediaMinimized() {
        return self::$isMediaMinimized;
    }

    /**
     * Set minimized media mode
     *
     * @param boolean $isMediaMinimized
     */
    public static function setMediaMinimized($isMediaMinimized) {
        self::$isMediaMinimized = !!$isMediaMinimized;
    }

    /**
     * Add callback to enqueue or register script at an appropriate moment.
     *
     * Scripts and styles should not be registered or enqueued until the
     * <code>wp_enqueue_scripts</code>, <code>admin_enqueue_scripts</code>, or
     * <code>login_enqueue_scripts</code> hooks.
     *
     * @param \Closure $callback
     */
    protected static function addEnqueueScriptsCallback($callback){
        if( did_action('wp_enqueue_scripts') ||
            did_action('admin_enqueue_scripts') ||
            did_action('login_enqueue_scripts') ){
            call_user_func($callback);
        }else{
            static::$callbackCounter ++;
            $delta = static::$callbackCounter / 10000;
            add_action('wp_enqueue_scripts', $callback, 10 + $delta);
            add_action('admin_enqueue_scripts', $callback, 10 + $delta);
            add_action('login_enqueue_scripts', $callback, 10 + $delta);
        }
    }

    /**
     * Alias to wp_deregister_script
     *
     * @param $handle
     */
    public static function unregisterScript($handle){
        $callback = function() use ($handle){
            wp_deregister_script($handle);
        };
        self::addEnqueueScriptsCallback($callback);
    }

    /**
     * Replace dependencies with combined handles
     * Heads up: This function should be called only if self::isMediaMinimized.
     *
     * @param string $handle
     * @param \WP_Dependencies $wpMedia
     * @param array $combined
     *
     * @return \_WP_Dependency
     */
    public static function resolveCombinedDependencies($handle, $wpMedia, $combined){
        $item = Util::getItem($wpMedia->registered, $handle);
        $itemDependencies = Util::getItem($item, 'deps', array());
        /**
         * Go through each handle dependency
         */
        foreach ($itemDependencies as $i => $d) {
            /**
             * If handle dependency is registered in minimized script
             */
            $combinedHandle = Util::getItem($combined, $d);
            if ($combinedHandle) {
                /**
                 * If handle dependency is registered in the same combined script,
                 * we don't need it as dependency, otherwise replace it with
                 * combined handle
                 */
                if(isset($combined[$handle]) && $combined[$handle] === $combinedHandle){
                    unset($itemDependencies[$i]);
                }else{
                    $itemDependencies[$i] = $combinedHandle;
                }
            }
        }
        $itemDependencies = array_unique($itemDependencies);
        $item->deps = $itemDependencies;
        
        return $item;
    }

	/**
	 * Alias to wp_register_script but checks if dependencies can be found inside minimized files
	 *
	 * @param string $handle
	 * @param string $src
	 * @param array $dependencies
	 * @param bool $version
	 * @param bool $inFooter
	 */
    public static function registerScript($handle, $src, $dependencies = array(), $version = false, $inFooter = true){

        if(empty($dependencies)){
            $dependencies = array();
        }
        $callback = function() use ($handle, $src, $dependencies, $version, $inFooter){
            wp_register_script($handle, $src, $dependencies, $version, $inFooter);
            if(self::$isMediaMinimized){
                global $wp_scripts;
                self::resolveCombinedDependencies($handle, $wp_scripts, self::$combinedScripts);
            }
        };

        self::addEnqueueScriptsCallback($callback);
    }

	/**
	 * Register script that contains minimized and combined scripts
	 *
	 * @param string $minHandle
	 * @param string $src
	 * @param array $handles
	 * @param bool $version
	 * @param bool $inFooter
	 */
    public static function registerCombinedScript($minHandle, $src, $handles, $version = false, $inFooter = true){
        if(self::isMediaMinimized()){
            $callback = function () use ($minHandle, $src, $handles, $version, $inFooter){
                global $wp_scripts;
                /**
                 * Associate minimized script handle with each script handle it contains
                 */
                foreach($handles as $handle){
                    self::$combinedScripts[ $handle ] = $minHandle;
                }

                $combinedHandles = array_values(self::$combinedScripts);

                foreach($wp_scripts->registered as $handle => $item){
                    if(!in_array($handle, $combinedHandles)){
                        self::resolveCombinedDependencies($handle, $wp_scripts, self::$combinedScripts);
                    }
                }

                $dependencies = array();
                /**
                 * Go through each handle
                 */
                foreach($handles as $handle){
                    $item = Util::getItem($wp_scripts->registered, $handle);
                    $itemDependencies = Util::getItem($item, 'deps', array());
                    $dependencies     = array_merge($dependencies, $itemDependencies);
                }
                $dependencies = array_unique($dependencies);
                wp_register_script($minHandle, $src, $dependencies, $version, $inFooter);
            };

            self::addEnqueueScriptsCallback($callback);
        }
    }

    /**
     * A little helper to update already registered script.
     * For instance, you may want to upgrade jQuery
     *
     * @param $handle
     * @param $src
     * @param null|array $dependencies
     * @param string|bool $version
     *
     * @return \_WP_Dependency|null
     */
    public static function updateScript($handle, $src, $dependencies = null, $version = false){
        $scripts = wp_scripts();
        if($scripts->registered[$handle]){
            /**
             * @var \_WP_Dependency $script
             */
            $script = $scripts->registered[$handle];
            $script->src = $src;
            if(is_array($dependencies)){
                $script->deps = $dependencies;
            }
            if($version){
                $script->ver = $version;
            }

            return $script;
        }

        return null;
    }

	/**
	 * This function can change default script rendering location: head or footer
	 * @param $handle
	 * @param bool $inFooter
	 */
	public static function setScriptLocation($handle, $inFooter = false){

        $callback = function() use ($handle, $inFooter){
            global $wp_scripts;
            if($wp_scripts->registered[$handle]){
                $wp_scripts->set_group($handle, false, $inFooter?1:0);
            }
        };

        self::addEnqueueScriptsCallback($callback);
	}

    /**
     * Enqueue script. Utilizes wp_enqueue_script().
     * However if detects registered minimized and combined version enqueue it instead.
     *
     * @param $handle
     * @param string|bool $src
     * @param array $dependencies
     * @param string|bool $ver
     * @param bool $inFooter
     */
    public static function enqueueScript($handle, $src = false, $dependencies = array(), $ver = false, $inFooter = true){
        if(empty($dependencies)){
            $dependencies = array();
        }
        $callback = function() use ($handle, $src, $dependencies, $ver, $inFooter) {
            if ( self::$isMediaMinimized && ! empty( self::$combinedScripts[ $handle ] ) ) {
                wp_enqueue_script( self::$combinedScripts[ $handle ] );
            } else {
                wp_enqueue_script( $handle, $src, $dependencies, $ver, $inFooter );
            }
        };
        self::addEnqueueScriptsCallback($callback);
    }

    /**
     * Add callback to enqueue or register style at an appropriate moment.
     *
     * Scripts and styles should not be registered or enqueued until the
     * <code>wp_enqueue_styles</code>, <code>admin_enqueue_styles</code>, or
     * <code>login_enqueue_styles</code> hooks.
     *
     * @param \Closure $callback
     */
    protected static function addEnqueueStylesCallback($callback){
        if( did_action('wp_enqueue_styles') ||
            did_action('admin_enqueue_styles') ||
            did_action('login_enqueue_styles') ){
//            $callback();
            call_user_func($callback);
        }else{
            static::$callbackCounter ++;
            $delta = static::$callbackCounter / 10000;
            add_action('wp_enqueue_styles', $callback, 10 + $delta);
            add_action('admin_enqueue_styles', $callback, 10 + $delta);
            add_action('login_enqueue_styles', $callback, 10 + $delta);
        }
    }

    /**
     * Alias to wp_deregister_style
     *
     * @param $handle
     */
    public static function unregisterStyle($handle){
        $callback = function() use ($handle){
            wp_deregister_style($handle);
        };
        self::addEnqueueScriptsCallback($callback);
    }

    /**
	 * Alias to wp_register_style but checks if dependencies can be found inside minimized files
	 *
	 * @param string $handle
	 * @param string $src
	 * @param array $dependencies
	 * @param bool $version
	 * @param string $media
	 */
    public static function registerStyle($handle, $src, $dependencies = array(), $version = false, $media = 'all'){
        if(empty($dependencies)){
            $dependencies = array();
        }
        $callback = function() use ($handle, $src, $dependencies, $version, $media){
            wp_register_style($handle, $src, $dependencies, $version, $media);
            if(self::$isMediaMinimized){
                global $wp_styles;
                self::resolveCombinedDependencies($handle, $wp_styles, self::$combinedStyles);
            }
        };
        self::addEnqueueScriptsCallback($callback);
    }

	/**
	 * Register script that contains minimized and combined styles
	 *
	 * @param string $minHandle
	 * @param string $src
	 * @param array $handles
	 * @param bool $version
	 * @param string $media
	 */
    public static function registerCombinedStyle($minHandle, $src, $handles, $version = false, $media = 'all'){
        if(self::isMediaMinimized()){
            $callback = function () use ($minHandle, $src, $handles, $version, $media){
                global $wp_styles;
                foreach($handles as $handle){
                    self::$combinedStyles[ $handle ] = $minHandle;
                }

                $combinedHandles = array_values(self::$combinedStyles);

                foreach($wp_styles->registered as $handle => $item){
                    if(!in_array($handle, $combinedHandles)){
                        self::resolveCombinedDependencies($handle, $wp_styles, self::$combinedStyles);
                    }
                }

                $dependencies = array();
                foreach($handles as $handle){
                    $item             = Util::getItem($wp_styles->registered, $handle);
                    $itemDependencies = Util::getItem($item, 'deps', array());
                    $dependencies = array_merge($dependencies, $itemDependencies);
                }
                $dependencies = array_unique($dependencies);
                wp_register_style($minHandle, $src, $dependencies, $version, $media);
            };
            self::addEnqueueScriptsCallback($callback);
        }
    }

    /**
     * A little helper to update already registered style.
     *
     * @param $handle
     * @param $src
     * @param null|array $dependencies
     * @param string|bool $version
     *
     * @return \_WP_Dependency|null
     */
    public static function updateStyle($handle, $src, $dependencies = null, $version = false){
        $styles = wp_styles();
        if($styles->registered[$handle]){
            /**
             * @var \_WP_Dependency $style
             */
            $style = $styles->registered[$handle];
            $style->src = $src;
            if(is_array($dependencies)){
                $style->deps = $dependencies;
            }
            if($version){
                $style->ver = $version;
            }

            return $style;
        }

        return null;
    }
    /**
     * Enqueue style. Utilizes wp_enqueue_style().
     * However if detects registered minimized and combined version enqueue it instead.
     *
     * @param $handle
     * @param string|bool $src
     * @param array $dependencies
     * @param string|bool $ver
     * @param string $media
     */
    public static function enqueueStyle($handle, $src = false, $dependencies = array(), $ver = false, $media = 'all'){
        if(empty($dependencies)){
            $dependencies = array();
        }
        $callback = function() use ($handle, $src, $dependencies, $ver, $media){
            if(self::$isMediaMinimized && !empty(self::$combinedStyles[$handle])){
                wp_enqueue_style(self::$combinedStyles[$handle]);
            }else{
                wp_enqueue_style($handle, $src, $dependencies, $ver, $media);
            }
        };
        self::addEnqueueScriptsCallback($callback);
    }

	/**
	 * Enqueue both script and style with the same $handle.
	 * Uses minimized versions if detects.
	 *
	 * @param string $handle
	 * @param bool $scriptInFooter
	 */
    public static function enqueueScriptStyle($handle, $scriptInFooter = true){
	    self::setScriptLocation($handle, $scriptInFooter);
        static::enqueueScript($handle);
	    static::enqueueStyle($handle);
    }

    /**
     * Store application resource folder url for future use
     *
     * @param $appId
     * @param $url
     */
    public static function setApplicationResourceFolderUrl($appId, $url){
        self::$applicationResourceFolderUrls[$appId] = $url;
    }

    /**
     * Get application resource folder url by application id
     *
     * @param $appId
     *
     * @return string
     */
    public static function getApplicationResourceFolderUrl($appId){
        return Util::getItem(self::$applicationResourceFolderUrls, $appId, '');
    }

    /**
     * Get application resource folder urls mapping by application id
     *
     * @return array
     */
    public static function getApplicationResourceFolderUrls(){
        return self::$applicationResourceFolderUrls;
    }
}