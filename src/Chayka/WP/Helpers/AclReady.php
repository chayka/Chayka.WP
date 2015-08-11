<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Helpers;

/**
 * Interface AclReady is implemented by Models and used by RestController to check user permissions
 *
 * @package Chayka\WP\Helpers
 */
interface AclReady {
	/**
     * Check if user has $privilege over the model instance
     *
	 * @param string $privilege
	 * @param \Chayka\WP\Models\UserModel|null $user
	 *
	 * @return mixed
	 */
	public function userCan($privilege, $user = null);
}