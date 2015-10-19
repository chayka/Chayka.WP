<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Helpers;

/**
 * Class NotificationHelper allows to create user notifications.
 * Currently only in Admin Area
 *
 * @package Chayka\WP\Helpers
 */
class NotificationHelper{

    /**
     * Show notice in admin area.
     *
     * @param $htmlMessage
     * @param bool|false $isError
     */
    public static function addAdminNotice($htmlMessage, $isError = false){
        $cls = $isError?'error':'updated';
        $output = sprintf('<div class="%s"><p>%s</p></div>', $cls, $htmlMessage);
        add_action( 'admin_notices', function () use($output) {
            echo $output;
        });
    }

}