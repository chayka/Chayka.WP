<?php

namespace Chayka\WP\Helpers;

use Chayka\Helpers\InputHelper;
use Chayka\WP\Models\CommentModel;
use Chayka\WP\Models\PostModel;
use Chayka\WP\Models\UserModel;
//require_once 'Zend/Acl.php';
//require_once 'Zend/Acl/Role.php';
//require_once 'Zend/Acl/Resource.php';

class AclHelper {

    const ROLE_GUEST = 'guest';
    const ROLE_SUBSCRIBER = 'subscriber';
    const ROLE_CONTRIBUTOR = 'contributor';
    const ROLE_AUTHOR = 'author';
    const ROLE_EDITOR = 'editor';
    const ROLE_ADMINISTRATOR = 'administrator';

    const ERROR_AUTH_REQUIRED = 'auth_required';
    const ERROR_PERMISSION_REQUIRED = 'permission_required';
    
//    protected static $acl = null;
    
//    public static function getInstance() {
//        if (empty(self::$acl)) {
//
//            $acl = new Zend_Acl();
//
//            // Add groups to the Role registry using Zend_Acl_Role
//            // Guest does not inherit access controls
//            $acl->addRole(new Zend_Acl_Role(self::ROLE_GUEST));
//
//            // Member inherits from guest
//            $acl->addRole(new Zend_Acl_Role(self::ROLE_SUBSCRIBER), 'guest');
//            $acl->addRole(new Zend_Acl_Role(self::ROLE_CONTRIBUTOR), 'subscriber');
//            $acl->addRole(new Zend_Acl_Role(self::ROLE_AUTHOR), 'contributor');
//            $acl->addRole(new Zend_Acl_Role(self::ROLE_EDITOR), 'author');
//
//            // Administrator does not inherit access controls
//            $acl->addRole(new Zend_Acl_Role(self::ROLE_ADMINISTRATOR));
//
////            $acl->add(new Zend_Acl_Resource('answer'));
//            $acl->add(new Zend_Acl_Resource('auth'));
////            $acl->add(new Zend_Acl_Resource('autocomplete'));
////            $acl->add(new Zend_Acl_Resource('comment'));
////            $acl->add(new Zend_Acl_Resource('question'));
////            $acl->add(new Zend_Acl_Resource('search'));
////            $acl->add(new Zend_Acl_Resource('user'));
////            $acl->add(new Zend_Acl_Resource('vote'));
//
//            $acl->allow(null, 'auth', array('login', 'logout', 'join', 'forgot-password', 'change-password', 'check-email', 'check-name'));
//
//            // Guest may only view content
////            $acl->allow(self::ROLE_GUEST, 'index');
//
//            // Member inherits view privilege from guest, but also needs additional privileges
////            $acl->allow('member', 'search');
////            $acl->allow('member', 'user', array('edit', 'get', 'update', 'profile'));
////            $acl->allow('member', 'comment');
////            $acl->allow('guest', 'question', 'view');
////            $acl->allow('member', 'question');
////            $acl->allow('member', 'answer');
////            $acl->allow('member', 'vote');
//
////            $acl->deny('advert', 'user', 'delete');
////            $acl->deny('advert', 'user', 'register');
//
//            // Administrator inherits nothing, but is allowed all privileges
//            $acl->allow(self::ROLE_ADMINISTRATOR);
////            $acl->deny('administrator', 'user', 'register');
//
//            self::$acl = $acl;
//        }
//
//        return self::$acl;
//    }
//
    public static function isAllowed($privilege = null, $resource = null, $role = null) {
        if (empty($role)) {
            $role = UserModel::currentUser()->getRole();
        }
        if (empty($resource)) {
            $resource = InputHelper::getParam('controller');
        }
        if (empty($privilege)) {
            $privilege = InputHelper::getParam('action');
        }

        $res = true; //self::getInstance()->isAllowed($role, $resource, $privilege);

        return $res;

    }
    
    public static function denyAccess($message = ''){
        if(!$message){
            $message = NlsHelper::_('Dear user, access to this page is forbidden for you.');
        }
        JsonHelper::respondError($message, self::ERROR_AUTH_REQUIRED, UserModel::currentUser());
    }

    public static function apiAuthRequired($message = ''){
        $userId = get_current_user_id();
        if(!$userId){
            if(!$message){
                $message = NlsHelper::_('You need to sign in to proceed');
            }
            JsonHelper::respondError($message, self::ERROR_AUTH_REQUIRED, UserModel::currentUser());
        }
    }
    
    public static function permissionRequired($message = '', $privilege = null, $resource = null, $role = null){
        if(!self::isAllowed($privilege, $resource, $role)){
            if(!$message){
                $message = NlsHelper::_('Access denied');
            }
            self::denyAccess($message);
            return false;
        }
        
        return true;
    }
    
//    public static function userHasPermission($message = '', $privilege = null, $resource = null, $role = null){
//        return self::permissionRequired($message, $privilege, $resource, $role);
//    }
    
    public static function isAuthorized(){
        $userId = get_current_user_id();
        return !empty ($userId);
    }

    /**
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
    
    public static function apiOwnershipRequired(/*PostModel*/ $obj, $message = ''){
        $valid = self::isOwner($obj);
        if(!$valid){
            if(!$message){
                $message = 'У вас недостаточно прав для модификации данного объекта';
            }
            JsonHelper::respondError($message, self::ERROR_PERMISSION_REQUIRED, UserModel::currentUser());
        }
    }

    /**
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
    
    public static function apiOwnershipForbidden(/*PostModel*/ $obj, $message = ''){
        if(self::isNotOwner($obj)){
            if(!$message){
                $message = 'Данная операция с собственным объектом невозможна';
            }
            JsonHelper::respondError($message, self::ERROR_PERMISSION_REQUIRED, UserModel::currentUser());
        }
    }
    
    public static function isUserRole($role, $user = null){
        if(!$user){
            $user = UserModel::currentUser();
        }elseif(!($user instanceof UserModel)){
            $user = UserModel::unpackDbRecord($user);
        }
        return ($user->hasRole($role));
    }

    public static function isAdmin($user = null){
        return self::isUserRole('administrator', $user);
    }

    public static function isEditor($user = null){
        return self::isUserRole('editor', $user);
    }

    public static function isAuthor($user = null){
        return self::isUserRole('author', $user);
    }

//    public static function show404() {
//        header("Location: /not-found-404/");
//        die();
//    }

}