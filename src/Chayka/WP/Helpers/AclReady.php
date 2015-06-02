<?php

namespace Chayka\WP\Helpers;

interface AclReady {
	/**
	 * @param string $privilege
	 * @param \Chayka\WP\Models\UserModel|null $user
	 *
	 * @return mixed
	 */
	public function userCan($privilege, $user = null);
}