<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 11.01.15
 * Time: 19:00
 */

namespace Chayka\WP\Helpers;

use Chayka\Helpers\Util;

class ResourceHelper {

    protected static $isMediaMinimized = false;

    protected static $minimizedStyles = array();

    protected static $minimizedScripts = array();

    /**
     * @return boolean
     */
    public static function isMediaMinimized() {
        return self::$isMediaMinimized;
    }

    /**
     * @param boolean $isMediaMinimized
     */
    public static function setMediaMinimized($isMediaMinimized) {
        self::$isMediaMinimized = $isMediaMinimized;
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
        if(self::$isMediaMinimized){
            foreach($dependencies as $i => $d){
                if(self::$minimizedScripts[$d]){
                    $dependencies[$i] = self::$minimizedScripts[$d];
                }
            }
            $dependencies = array_unique($dependencies);
        }
        wp_register_script($handle, $src, $dependencies, $version, $inFooter);
    }

	/**
	 * Register script that contains minimized and concatenated scripts
	 *
	 * @param string $minHandle
	 * @param string $src
	 * @param array $handles
	 * @param bool $version
	 * @param bool $inFooter
	 */
    public static function registerMinimizedScript($minHandle, $src, $handles, $version = false, $inFooter = true){
        global $wp_scripts;
        $dependencies = array();
	    foreach($handles as $handle) {
		    self::$minimizedScripts[ $handle ] = $minHandle;
	    }
        foreach($handles as $handle){
            $item = Util::getItem($wp_scripts->registered, $handle);
            $itemDependencies = Util::getItem($item, 'deps', array());
            foreach ($itemDependencies as $i => $d) {
                if (self::$minimizedScripts[$d]) {
                    if(self::$minimizedScripts[$d] === $minHandle){
                        unset($itemDependencies[$i]);
                    }else{
                        $itemDependencies[$i] = self::$minimizedScripts[$d];
                    }
                }
            }
            $itemDependencies = array_unique($itemDependencies);
            $dependencies = array_merge($dependencies, $itemDependencies);
        }
        $dependencies = array_unique($dependencies);
        wp_register_script($minHandle, $src, $dependencies, $version, $inFooter);
    }

	/**
	 * This function can change default script rendering location: head or footer
	 * @param $handle
	 * @param bool $inFooter
	 */
	public static function setScriptLocation($handle, $inFooter = false){
		global $wp_scripts;
		if($wp_scripts->registered[$handle]){
			$wp_scripts->set_group($handle, false, $inFooter?1:0);
		}
	}

    /**
     * Enqueue script. Utilizes wp_enqueue_script().
     * However if detects registered minimized and concatenated version enqueue it instead.
     *
     * @param $handle
     * @param string|bool $src
     * @param array $dependencies
     * @param string|bool $ver
     * @param bool $inFooter
     */
    public static function enqueueScript($handle, $src = false, $dependencies = array(), $ver = false, $inFooter = true){
        if(self::$isMediaMinimized && !empty(self::$minimizedScripts[$handle])){
            wp_enqueue_script(self::$minimizedScripts[$handle]);
        }else{
            wp_enqueue_script($handle, $src, $dependencies, $ver, $inFooter);
        }
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
        if(self::$isMediaMinimized) {
            foreach ($dependencies as $i => $d) {
                if (self::$minimizedStyles[$d]) {
                    $dependencies[$i] = self::$minimizedStyles[$d];
                }
            }
            $dependencies = array_unique($dependencies);
        }
        wp_register_style($handle, $src, $dependencies, $version, $media);
    }

	/**
	 * Register script that contains minimized and concatenated styles
	 *
	 * @param string $minHandle
	 * @param string $src
	 * @param array $handles
	 * @param bool $version
	 * @param string $media
	 */
    public static function registerMinimizedStyle($minHandle, $src, $handles, $version = false, $media = 'all'){
        global $wp_styles;
        $dependencies = array();
	    foreach($handles as $handle) {
		    self::$minimizedStyles[ $handle ] = $minHandle;
	    }
        foreach($handles as $handle){
            $item = Util::getItem($wp_styles->registered, $handle);
            $itemDependencies = Util::getItem($item, 'deps', array());
            foreach ($itemDependencies as $i => $d) {
                if (self::$minimizedStyles[$d]) {
                    if(self::$minimizedStyles[$d] === $minHandle){
                        unset($itemDependencies[$i]);
                    }else{
                        $itemDependencies[$i] = self::$minimizedStyles[$d];
                    }
                }
            }
            $itemDependencies = array_unique($itemDependencies);
            $dependencies = array_merge($dependencies, $itemDependencies);
        }
        $dependencies = array_unique($dependencies);
        wp_register_style($minHandle, $src, $dependencies, $version, $media);
    }

    /**
     * Enqueue style. Utilizes wp_enqueue_style().
     * However if detects registered minimized and concatenated version enqueue it instead.
     *
     * @param $handle
     * @param string|bool $src
     * @param array $dependencies
     * @param string|bool $ver
     * @param string $media
     */
    public static function enqueueStyle($handle, $src = false, $dependencies = array(), $ver = false, $media = 'all'){
        if(self::$isMediaMinimized && !empty(self::$minimizedStyles[$handle])){
            wp_enqueue_style(self::$minimizedStyles[$handle]);
        }else{
            wp_enqueue_style($handle, $src, $dependencies, $ver, $media);
        }
    }

    /**
     * Enqueue both script and style with the same $handle.
     * Uses minimized versions if detects.
     *
     * @param string $handle
     */
    public static function enqueueScriptStyle($handle, $scriptInFooter = true){
	    self::setScriptLocation($handle, $scriptInFooter);
        self::enqueueScript($handle);
        self::enqueueStyle($handle);
    }
}