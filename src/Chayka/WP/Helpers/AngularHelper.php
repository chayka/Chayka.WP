<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Helpers;

/**
 * Class AngularHelper extends ResourceHelper.
 * The main idea of this helper is that all angular scripts should be registered and enqueued
 * using this helper.
 *
 * This way one can get the list of modules enqueued using AngularHelper::getQueue().
 * And that allows us to angular.bootstrap() those modules upon html page.
 * In fact that is done in Chayka.Core.wpp plugin automatically.
 *
 * For this feature to work consider storing each angular module in a separate js file
 * and register it under the same handle.
 *
 * e.g. angular.module('chayka-modals') goes to AngularHelper::registerScript('chayka-modals', ...)
 *
 * @package Chayka\WP\Helpers
 */
class AngularHelper extends ResourceHelper {

	/**
     * Array of registered angular modules (scripts).
	 * @var [string]
	 */
	public static $registered = [];

	/**
     * Hash map of callbacks that are triggered when angular module is enqueued
	 * @var [callback]
	 */
	public static $callbacks = [];

	/**
     * Array of enqueued angular modules
	 * @var [string]
	 */
	public static $queue = [];

	/**
     * Hash map of angular module dependencies
	 * @var []
	 */
	public static $ngDependencies = [];

	/**
	 * This method works almost the same like ResourceHelper::registerScript(),
     * and that one simply calls wp_register_script at the right moment.
     *
     * There are two differences however.
     * 1. It automatically ensures 'angular' as dependency
     * 2. It registers the handle as the module to be bootstrapped when script will be enqueued.
     *
     * e.g. You call somewhere
     *  AngularHelper::enqueueScript('chayka-modals');
     *  AngularHelper::enqueueScript('chayka-spinners');
     * That way you can call later AngularHelper::getQueue() to render
     * <script>
     *  angular.bootstrap(document.body, ['chayka-modals', 'chayka-spinner']);
     * </script>
     *
     * In fact this logic is already implemented in Chayka.Core.wpp!
     *
	 * @param string $handle
	 * @param string $src
	 * @param array $dependencies
	 * @param callable|null $enqueueCallback
	 * @param bool $version
	 * @param bool $inFooter
	 */
	public static function registerScript($handle, $src, $dependencies = [], $enqueueCallback = null, $version = false, $inFooter = true){
		self::$registered[]=$handle;
		self::$ngDependencies[$handle] = $dependencies;
		if($enqueueCallback){
			self::$callbacks[$handle] = $enqueueCallback;
		}
		if(!is_array($dependencies)){
            $dependencies = [];
        }
        if(!in_array('angular', $dependencies)){
            array_unshift($dependencies, 'angular');
        }
		parent::registerScript($handle, $src, $dependencies, $version, $inFooter);
	}

	/**
     * This method works almost the same like ResourceHelper::enqueueScript(),
     * and that one simply calls wp_enqueue_script at the right moment.
     *
     * There are two differences however.
     * 1. It automatically ensures 'angular' as dependency
     * 2. It registers the handle as the module to be bootstrapped when script will be enqueued.
     *
     * e.g. You call somewhere
     *  AngularHelper::enqueueScript('chayka-modals');
     *  AngularHelper::enqueueScript('chayka-spinners');
     * That way you can call later AngularHelper::getQueue() to render
     * <script>
     *  angular.bootstrap(document.body, ['chayka-modals', 'chayka-spinner']);
     * </script>
     *
     * In fact this logic is already implemented in Chayka.Core.wpp!
     *
	 * @param $handle
	 * @param bool|string $src
	 * @param array $dependencies
	 * @param callable|null $enqueueCallback
	 * @param bool $version
	 * @param bool $inFooter
	 */
	public static function enqueueScript($handle, $src = false, $dependencies = [], $enqueueCallback = null, $version = false, $inFooter = true){
		if($src) {
			self::registerScript( $handle, $src, $dependencies, $enqueueCallback, $version, $inFooter );
		}
		if(!in_array($handle, self::$queue) && in_array($handle, self::$registered)){
			if(!empty(self::$ngDependencies[$handle])){
				foreach(self::$ngDependencies[$handle] as $dep){
					self::enqueueScript($dep);
				}
			}
			if(isset(self::$callbacks[$handle])){
				$cb = self::$callbacks[$handle];
				self::addEnqueueScriptsCallback($cb);
			}
			self::$queue[]=$handle;
		}

		parent::enqueueScript($handle);
	}

    /**
     * This method works almost the same like ResourceHelper::registerStyle(),
     * and that one simply calls wp_register_style at the right moment.
     *
     * It automatically ensures 'angular' as dependency.
     *
     * @param string $handle
     * @param string $src
     * @param array $dependencies
     * @param bool|false $version
     * @param string $media
     */
    public static function registerStyle($handle, $src, $dependencies = array(), $version = false, $media = 'all'){
        if(!is_array($dependencies)){
            $dependencies = [];
        }
        if(!in_array('angular', $dependencies)){
            array_unshift($dependencies, 'angular');
        }
        parent::registerStyle($handle, $src, $dependencies, $version, $media);
    }

    /**
     * This method works almost the same like ResourceHelper::enqueueStyle(),
     * and that one simply calls wp_enqueue_style at the right moment.
     *
     * It automatically ensures 'angular' as dependency.
     * @param string $handle
     * @param bool|false $src
     * @param array $dependencies
     * @param bool|false $version
     * @param string $media
     */
    public static function enqueueStyle($handle, $src = false, $dependencies = array(), $version = false, $media = 'all'){
        if($src){
            self::registerStyle($handle, $src, $dependencies, $version, $media);
        }
        parent::enqueueStyle($handle);
    }

    /**
     * Get the list of angular modules that should be bootstrapped
     *
     * @return array
     */
	public static function getQueue(){
		return self::$queue;
	}
}