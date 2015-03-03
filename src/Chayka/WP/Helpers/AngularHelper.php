<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 03.03.15
 * Time: 12:44
 */

namespace Chayka\WP\Helpers;


class AngularHelper extends ResourceHelper {

	/**
	 * @var [string]
	 */
	public static $registered = [];

	/**
	 * @var [callback]
	 */
	public static $callbacks = [];

	/**
	 * @var [string]
	 */
	public static $queue = [];

	/**
	 * @var []
	 */
	public static $dependencies = [];

	/**
	 * @param string $handle
	 * @param string $src
	 * @param array $dependencies
	 * @param callable|null $enqueueCallback
	 * @param bool $version
	 * @param bool $inFooter
	 */
	public static function registerScript($handle, $src, $dependencies = [], $enqueueCallback = null, $version = false, $inFooter = true){
		self::$registered[]=$handle;
		self::$dependencies[$handle] = $dependencies;
		if($enqueueCallback){
			self::$callbacks[$handle] = $enqueueCallback;
		}
		parent::registerScript($handle, $src, $dependencies, $version, $inFooter);
	}

	/**
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
			if(isset(self::$dependencies[$handle])){
				foreach(self::$dependencies[$handle] as $dep){
					self::enqueueScript($dep);
				}
			}
			if(isset(self::$callbacks[$handle])){
				$cb = self::$callbacks[$handle];
				$cb();
			}
			self::$queue[]=$handle;
		}

		parent::enqueueScript($handle);
	}

	public static function getQueue(){
		return self::$queue;
	}
}