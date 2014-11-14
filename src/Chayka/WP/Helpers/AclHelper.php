<?php

namespace Chayka\WP\Helpers;

use Chayka\Helpers\InputHelper;
use Chayka\WP\Models\CommentModel;
use Chayka\WP\Models\PostModel;
use Chayka\WP\Models\UserModel;

class AclHelper {

    const ROLE_GUEST = 'guest';
    const ROLE_SUBSCRIBER = 'subscriber';
    const ROLE_CONTRIBUTOR = 'contributor';
    const ROLE_AUTHOR = 'author';
    const ROLE_EDITOR = 'editor';
    const ROLE_ADMINISTRATOR = 'administrator';

    const ERROR_AUTH_REQUIRED = 'auth_required';
    const ERROR_PERMISSION_REQUIRED = 'permission_required';

    /**
     * Register role
     *
     * @param string $role
     * @param string $displayName
     * @param array $capabilities
     * @return null|\WP_Role
     */
    public static function addRole($role, $displayName = '', $capabilities = array()){
        return add_role($role, $displayName?$displayName:$role, $capabilities);
    }

    /**
     * Allow capability for resource to the role
     *
     * @param string $role
     * @param string|array $capability
     * @param string|array $resource
     * @param bool $grant
     */
    public static function allow($role, $capability, $resource = '', $grant = true){
        if(is_array($resource)){
            foreach($resource as $res){
                self::allow($role, $capability, $res);
            }
            return;
        }
        if(is_array($capability)){
            foreach($capability as $cap){
                self::allow($role, $cap, $resource);
            }
            return;
        }
        $role = get_role($role);
        if(!$role){
            $role = self::addRole($role);
        }
        $role->add_cap($resource?$capability.'_'.$resource:$capability, $grant);

        return;
    }

    /**
     * Deny capability for resource to the role
     *
     * @param $role
     * @param $capability
     * @param string $resource
     */
    public static function deny($role, $capability, $resource = ''){
        self::allow($role, $capability, $resource, false);
    }

    /**
     * Check if current user has privilege on specified resource
     *
     * @param null $privilege
     * @param null $resource
     * @return bool
     */
    public static function isAllowed($privilege = null, $resource = null) {
        if (empty($resource)) {
            $resource = InputHelper::getParam('controller', '*');
        }
        if (empty($privilege)) {
            $privilege = InputHelper::getParam('action', '*');
        }

        $res = self::isAdmin()
            || current_user_can($privilege)
            || current_user_can($privilege.'_'.$resource)
            || $privilege !== '*' && current_user_can('*_'.$resource)
            || $resource !== '*' &&  $privilege !== '*' && current_user_can('*_*'); //self::getInstance()->isAllowed($role, $resource, $privilege);

        return $res;
    }

    /**
     * Check whether user is logged in
     *
     * @return bool
     */
    public static function isLoggedIn(){
        $userId = get_current_user_id();
        return !!$userId;
    }

    /**
     * Respond with api error if user is not logged in
     *
     * @param string $message
     */
    public static function apiAuthRequired($message = ''){
        if(!self::isLoggedIn()){
            if(!$message){
                $message = NlsHelper::_('You need to sign in to proceed');
            }
            JsonHelper::respondError($message, self::ERROR_AUTH_REQUIRED, UserModel::currentUser());
        }
    }

    /**
     * Respond with api error if user has no privilege for specified resource
     *
     * @param string $message
     */
    public static function apiPermissionRequired($message = '', $privilege = null, $resource = null){
        if(!self::isAllowed($privilege, $resource)){
            if(!$message){
                $message = NlsHelper::_('Access denied');
            }
            JsonHelper::respondError($message, self::ERROR_PERMISSION_REQUIRED, UserModel::currentUser());
        }
        
    }
    
    /**
     * Check if user is the owner of specified object
     *
     * @param PostModel|CommentModel $obj
     * @return bool
     */
    public static function isOwner($obj){
        $user = UserModel::currentUser();
        $isOwner = false;
        if($user->getId()){
            if($obj instanceof UserModel){
                $isOwner = $obj->getId() == $user->getId() ;
            }else{
                $isOwner = $obj->getUserId() == $user->getId();
            }
        }
        return ($isOwner || $user->hasRole('administrator'));
    }

    /**
     * Respond with api error if user is not the owner of specified object
     *
     * @param PostModel|CommentModel $obj
     * @param string $message
     */
    public static function apiOwnershipRequired($obj, $message = ''){
        $valid = self::isOwner($obj);
        if(!$valid){
            if(!$message){
                $message = 'У вас недостаточно прав для модификации данного объекта';
            }
            JsonHelper::respondError($message, self::ERROR_PERMISSION_REQUIRED, UserModel::currentUser());
        }
    }

    /**
     * Check if user is not the owner of specified object
     *
     * @param PostModel|CommentModel $obj
     * @return bool
     */
    public static function isNotOwner(/*PostModel*/ $obj){
        $user = UserModel::currentUser();
        if($obj instanceof UserModel){
            $isOwner = $obj->getId() == $user->getId();
        }else{
            $isOwner = $obj->getUserId() == $user->getId();
        }
        return (!$isOwner || $user->hasRole('administrator'));
    }

    /**
     * Respond with api error if user is the owner of specified object
     *
     * @param $obj
     * @param string $message
     */
    public static function apiOwnershipForbidden($obj, $message = ''){
        if(self::isNotOwner($obj)){
            if(!$message){
                $message = 'Данная операция с собственным объектом невозможна';
            }
            JsonHelper::respondError($message, self::ERROR_PERMISSION_REQUIRED, UserModel::currentUser());
        }
    }

    /**
     * Check if user has role
     *
     * @param string $role
     * @param null|UserModel $user
     * @return bool
     */
    public static function userHasRole($role, $user = null){
        if(!$user){
            $user = UserModel::currentUser();
        }elseif(!($user instanceof UserModel)){
            $user = UserModel::unpackDbRecord($user);
        }
        return ($user->hasRole($role));
    }

    /**
     * Check if user is admin
     *
     * @param null|UserModel $user
     * @return bool
     */
    public static function isAdmin($user = null){
        return self::userHasRole('administrator', $user);
    }

    /**
     * Check if user is editor
     *
     * @param null|UserModel $user
     * @return bool
     */
    public static function isEditor($user = null){
        return self::userHasRole('editor', $user);
    }

    /**
     * Check if user is author
     *
     * @param null|UserModel $user
     * @return bool
     */
    public static function isAuthor($user = null){
        return self::userHasRole('author', $user);
    }

    /**
     * Check if user is author
     *
     * @param null|UserModel $user
     * @return bool
     */
    public static function isContributor($user = null){
        return self::userHasRole('contributor', $user);
    }

    /**
     * Check if user is author
     *
     * @param null|UserModel $user
     * @return bool
     */
    public static function isSubscriber($user = null){
        return self::userHasRole('subscriber', $user);
    }

}