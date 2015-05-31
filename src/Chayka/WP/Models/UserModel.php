<?php

namespace Chayka\WP\Models;

use Chayka\Helpers\Util;
use Chayka\Helpers\JsonReady;
use Chayka\Helpers\InputReady;
use Chayka\Helpers\InputHelper;
use Chayka\Helpers\DateHelper;
use Chayka\WP\Helpers\DbReady;
use Chayka\WP\Helpers\AclHelper;
use Chayka\WP\Queries\UserQuery;
use DateTime;
use WP_User_Query;
use WP_User;
use WP_Error;

//NlsHelper::load('application/models', 'UserModel');

/**
 * Class implemented to handle user actions and manipulations
 * Used for authentification, registration, update, delete and userpics management 
 *
 */
class UserModel implements DbReady, JsonReady, InputReady{
    const SESSION_KEY = '_user';
    
    protected static $userCacheById = array();
    protected static $userCacheByEmail = array();
    protected static $userCacheByLogin = array();
    
    protected static $jsonMetaFields = array();

    protected static $currentUser;
	protected static $validationErrors;

	protected $id;

    protected $login;
    
    protected $password;
    
    protected $nicename;
    
    protected $url;
    
    protected $displayName;
    
    protected $firstName;
    
    protected $lastName;
    
    protected $description;
    
    protected $richEditing;
    
    protected $role;
    
    protected $capabilities;
    
    protected $email;

    protected $registered;

    protected $jabber;
    
    protected $aim;
    
    protected $yim;
    
    protected $wpUser;

    /**
     * UserModel constructor
     *
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialization with guest data
     */
    public function init(){
        $this->setId(0);
        $this->setLogin('guest');
        $this->setEmail('');
        $this->setRegistered(new DateTime());
        
    }

    /**
     * Get user id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set user id
     *
     * @param integer $id
     * @return UserModel
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get user login
     *
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * Set user login
     *
     * @param $login
     * @return $this
     */
    public function setLogin($login) {
        $this->login = $login;
        return $this;
    }

    /**
     * Get user password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return UserModel
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * Get user nicename (slug)
     *
     * @return string
     */
    public function getNicename() {
        return $this->nicename;
    }

    /**
     * Set user nicename (slug)
     *
     * @param $nicename
     * @return $this
     */
    public function setNicename($nicename) {
        $this->nicename = $nicename;
        return $this;
    }

    /**
     * Get user's site url.
     * Not the user's profile link.
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set user's site url.
     * Not the user's profile link.
     *
     * @param string $url
     * @return UserModel
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * Get user display name
     *
     * @return string
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * Set user display name
     *
     * @param string $displayName
     * @return UserModel
     */
    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set first name
     *
     * @param string $firstName
     * @return UserModel
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get last name
     *
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * Set last name
     *
     * @param $lastName
     * @return UserModel
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get user description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set user description
     *
     * @param string $description
     * @return UserModel
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * Get 'rich_editing'
     *
     * @return mixed
     */
    public function getRichEditing() {
        return $this->richEditing;
    }

    /**
     * Set 'rich_editing'
     *
     * @param $richEditing
     * @return UserModel
     */
    public function setRichEditing($richEditing) {
        $this->richEditing = $richEditing;
        return $this;
    }

    /**
     * Get set of user capabilities
     *
     * @return array
     */
    public function getCapabilities(){
        global $wpdb;
        if(!$this->capabilities){
            $this->capabilities = $this->getMeta($wpdb->prefix.'capabilities', true);
        }
        return $this->capabilities?$this->capabilities:array();
    }

    /**
     * Check if user has role
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole($role){
        return $this->hasCapability($role);
    }

    /**
     * Check if user has capability
     *
     * @param $capability
     * @return boolean
     */
    public function hasCapability($capability){
        return !!Util::getItem($this->getCapabilities(), $capability);
    }

    /**
     * Get the best (most powerful) available role
     *
     * @return string
     */
    public function getRole() {
        if($this->getId() && !$this->role){
            $standardRoles = array(
                'administrator',
                'editor',
                'author',
                'contributor',
                'subscriber'
            );
            
            foreach ($standardRoles as $role){
                if($this->hasRole($role)){
                    $this->role = $role;
                    break;
                }
            }

            if(!$this->role){
                $this->role = "guest";
            }
        }
        return $this->role;
    }

    /**
     * Set user role
     * TODO: check if it saves somehow
     *
     * @param $role
     * @return UserModel
     */
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set user model
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * Get user registration datetime
     *
     * @return DateTime
     */
    public function getRegistered() {
        return $this->registered;
    }

    /**
     * Set user registration datetime
     *
     * @param DateTime $registered
     * @return UserModel
     */
    public function setRegistered($registered) {
        $this->registered = $registered;
        return $this;
    }

    /**
     * Set jabebr
     *
     * @return string
     */
    public function getJabber() {
        return $this->jabber;
    }

    /**
     * Get jabber
     *
     * @param string $jabber
     * @return UserModel
     */
    public function setJabber($jabber) {
        $this->jabber = $jabber;
        return $this;
    }

    /**
     * Get aim
     *
     * @return string
     */
    public function getAim() {
        return $this->aim;
    }

    /**
     * Set aim
     *
     * @param string $aim
     * @return UserModel
     */
    public function setAim($aim) {
        $this->aim = $aim;
        return $this;
    }

    /**
     * Get yim
     *
     * @return string
     */
    public function getYim() {
        return $this->yim;
    }

    /**
     * Set yim
     *
     * @param string $yim
     * @return UserModel
     */
    public function setYim($yim) {
        $this->yim = $yim;
        return $this;
    }

    /**
     * Get user profile link
     *
     * @return string
     */
    public function getProfileLink(){
        return get_author_posts_url($this->getId(), $this->getNicename());
    }

    /**
     * Get original WP_User model if one is stored
     *
     * @return object|WP_User
     */
    public function getWpUser() {
        return $this->wpUser;
    }

    /**
     * Set original WP_User model
     *
     * @param object|WP_User $wpUser
     * @return $this
     */
    public function setWpUser($wpUser) {
        $this->wpUser = $wpUser;
        return $this;
    }

	/**
	 * Magic getter that allows to use UserModel where wpUser should be used
	 *
	 * @param $name
	 * @return mixed
	 */
	public function __get($name) {
		return Util::getItem($this->wpUser, $name);
	}

    /**
     * Get user meta single key-value pair or all key-values
     * 
     * @param int $userId Comment ID.
     * @param string $key Optional. The meta key to retrieve. By default, returns data for all keys.
     * @param bool $single Whether to return a single value.
     * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
     */
    public static function getUserMeta($userId, $key = '', $single = true){
        $meta = get_user_meta($userId, $key, $single);
        if(!$key && $single && $meta && is_array($meta)){
            $m = array();
            foreach($meta as $k => $values){
                $m[$k]= is_array($values)?reset($values):$values;
            }
            
            return $m;
        }
        return $meta;
    }

    /**
     * Update user meta value for the specified key in the DB
     * 
     * @param integer $userId
     * @param string $key
     * @param string $value
     * @param string $oldValue
     * @return bool False on failure, true if success.
     */
    public static function updateUserMeta($userId, $key, $value, $oldValue = ''){
        return update_user_meta($userId, $key, $value, $oldValue);
    }

	/**
	 * Remove metadata matching criteria from a user.
	 *
	 * You can match based on the key, or key and value. Removing based on key and
	 * value, will keep from removing duplicate metadata with the same key. It also
	 * allows removing all metadata matching key, if needed.
	 *
	 * @param integer $userId
	 * @param string $key
	 * @param string $oldValue
	 *
	 * @return bool
	 */
	public static function deleteUserMeta($userId, $key, $oldValue = ''){
		return delete_user_meta($userId, $key, $oldValue);
	}

    /**
     * Get user meta single key-value pair or all key-values
     * 
     * @param string $key Optional. The meta key to retrieve. By default, returns data for all keys.
     * @param bool $single Whether to return a single value.
     * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
     */
    public function getMeta($key = '', $single = true) {
        return  self::getUserMeta($this->getId(), $key, $single);
    }

    /**
     * Update user meta value for the specified key in the DB
     * 
     * @param string $key
     * @param string $value
     * @param string $oldValue
     * @return bool False on failure, true if success.
     */
    public function updateMeta($key, $value, $oldValue = '') {
        self::updateUserMeta($this->getId(), $key, $value, $oldValue);
    }

	/**
	 * @param $key
	 * @param string $oldValue
	 */
	public function deleteMeta($key, $oldValue = ''){
		self::deleteUserMeta($this->getId(), $key, $oldValue);
	}

    /**
     * DbReady method, returns corresponding DB Table ID column name
     *
     * @return string
     */
    public static function getDbIdColumn() {
        return 'ID';
    }

    /**
     * DbReady method, returns corresponding DB Table name
     *
     * @return string
     */
    public static function getDbTable() {
        global $wpdb;
        return $wpdb->users;
    }

    /**
     * Unpacks db record while fetching model from DB
     *
     * @param object $wpRecord
     * @return UserModel
     */
    public static function unpackDbRecord( $wpRecord){
        
        $obj = new self();

        $obj->setId(Util::getItem($wpRecord, 'ID'));
        $obj->setLogin(Util::getItem($wpRecord, 'user_login'));
        $obj->setEmail(Util::getItem($wpRecord, 'user_email'));
        $obj->setNicename(Util::getItem($wpRecord, 'user_nicename'));
        $obj->setUrl(Util::getItem($wpRecord, 'user_url'));
        $obj->setDisplayName(Util::getItem($wpRecord, 'display_name'));
        $obj->setFirstName(Util::getItem($wpRecord, 'first_name'));
        $obj->setLastName(Util::getItem($wpRecord, 'last_name'));
        $obj->setDescription(Util::getItem($wpRecord, 'description'));
        $obj->setRichEditing(Util::getItem($wpRecord, 'rich_editing'));
        $obj->setRegistered(DateHelper::dbStrToDatetime(Util::getItem($wpRecord, 'user_registered')));
        $obj->setRole(Util::getItem($wpRecord, 'role'));
        $obj->setJabber(Util::getItem($wpRecord, 'jabber'));
        $obj->setAim(Util::getItem($wpRecord, 'aim'));
        $obj->setYim(Util::getItem($wpRecord, 'yim'));
        $obj->setWpUser($wpRecord);
        
        self::$userCacheById[$obj->getId()] = $obj;
        self::$userCacheByEmail[$obj->getEmail()] = $obj->getId();
        self::$userCacheByLogin[$obj->getLogin()] = $obj->getId();
        
        return $obj;
    }

    /**
     * Packs model into assoc array before committing to DB
     *
     * @param boolean $forUpdate
     * @return array
     */
    public function packDbRecord($forUpdate = true){
        $dbRecord = array();
        if($forUpdate){
            $dbRecord['ID'] = $this->getId();
        }
        if(!empty($this->password)){
            $dbRecord['user_pass'] = $forUpdate?
                wp_hash_password($this->getPassword()):
                $this->getPassword();
        }
        $dbRecord['user_login'] = $this->getLogin();
        $dbRecord['user_nicename'] = $this->getNicename();
        $dbRecord['user_url'] = $this->getUrl();
        $dbRecord['user_email'] = $this->getEmail();
        $dbRecord['display_name'] = $this->getDisplayName();
        $dbRecord['first_name'] = $this->getFirstName();
        $dbRecord['last_name'] = $this->getLastName();
        $dbRecord['description'] = $this->getDescription();
        $dbRecord['rich_editing'] = $this->getRichEditing();
        $dbRecord['user_registered'] = DateHelper::datetimeToDbStr($this->getRegistered());
        $dbRecord['role'] = $this->getRole();
        $dbRecord['jabber'] = $this->getJabber();
        $dbRecord['aim'] = $this->getAim();
        $dbRecord['yim'] = $this->getYim();
        
        return $dbRecord;
    }

    /**
     * Inserts new model to DB, returns autogenerated ID
     *
     * @return integer
     */
    public function insert(){
        $this->setRegistered(new DateTime());
        $dbRecord = $this->packDbRecord(false);
        $id = wp_insert_user($dbRecord);
        $this->setId($id);
        return $id;
    }

    /**
     * Update db record
     *
     * @return int|WP_Error The value 0 or WP_Error on failure. The user ID on success.
     */
    public function update(){
        $dbRecord = $this->packDbRecord();
        unset($dbRecord['user_login']);
        unset($dbRecord['user_registered']);
        return wp_update_user($dbRecord);
    }

    /**
     * Delete record form DB
     *
     * @param integer $reassignUserId
     * @return boolean False on failure
     */
    public function delete($reassignUserId = 0){
        return self::deleteById($this->getId(), $reassignUserId);
    }
    
    /**
     * Deletes user with the specified $userId from db table
     *
     * @param integer $userId
     * @param integer $reassignUserId
     * @return boolean
     */
    public static function deleteById($userId = 0, $reassignUserId = 0) {
        require_once 'wp-admin/includes/user.php';
        $item = Util::getItem(self::$userCacheById, $userId);
        if($item){
            unset(self::$userCacheByEmail[$item->getEmail()]);
            unset(self::$userCacheByLogin[$item->getLogin()]);
            unset(self::$userCacheById[$userId]);
        }
        return wp_delete_user( $userId, $reassignUserId );
    }

    /**
     * Select user by id
     *
     * @param integer $id
     * @param boolean $useCache
     * @return UserModel 
     */
    public static function selectById($id, $useCache = true){
        if($useCache){
            $item = Util::getItem(self::$userCacheById, $id);
            if($item){
                return $item;
            }
        }
        $wpRecord = get_user_by('id', $id);
        return $wpRecord?self::unpackDbRecord($wpRecord):null;
    }

    /**
     * Select user by login
     *
     * @param string $login
     * @param boolean $useCache
     * @return UserModel
     */
    public static function selectByLogin($login, $useCache = true){
        if($useCache){
            $id = Util::getItem(self::$userCacheByLogin, $login);
            $item = Util::getItem(self::$userCacheById, $id);
            if($item){
                return $item;
            }
        }
        $wpRecord = get_user_by('login', $login);
        return $wpRecord?self::unpackDbRecord($wpRecord):null;
    }

    /**
     * Select user by email
     *
     * @param $email
     * @param bool $useCache
     * @return UserModel
     */
    public static function selectByEmail($email, $useCache = true){
        if($useCache){
            $id = Util::getItem(self::$userCacheByEmail, $email);
            $item = Util::getItem(self::$userCacheById, $id);
            if($item){
                return $item;
            }
        }
        $wpRecord = get_user_by('email', $email);
        return $wpRecord?self::unpackDbRecord($wpRecord):null;
    }

    /**
     * Select user by slug (nicename)
     *
     * @param $slug
     * @return UserModel
     */
    public static function selectBySlug($slug){
        $wpRecord = get_user_by('slug', $slug);
        return $wpRecord?self::unpackDbRecord($wpRecord):null;
    }

    /**
     * Select users by filtering args.
     * Use UserModel::query() helper instead.
     *
     * @param $wpUserQueryArgs
     * @return array
     */
    public static function selectUsers($wpUserQueryArgs){
        $users = array();
        $args = wp_parse_args( $wpUserQueryArgs );
        $args['count_total'] = true;

        $user_search = new WP_User_Query($args);

        $dbRecords = (array) $user_search->get_results();
        foreach ($dbRecords as $dbRecord) {
            $users[] = self::unpackDbRecord($dbRecord);
        }
        
        return $users;
    }

    /**
     * Get query helper instance.
     *
     * @return UserQuery
     */
    public static function query(){
        return new UserQuery();
    }
    
    /**
     * Get current user instance
     *
     * @return UserModel
     */
    public static function currentUser(){

        if(empty(self::$currentUser)){

            $current_user = wp_get_current_user();

            if($current_user && $current_user->ID){
                self::$currentUser = self::unpackDbRecord($current_user);
            }else{
                self::$currentUser = new UserModel();
            }

        }
        
        return self::$currentUser;
    }

    /**
     * Set meta fields that should be populated to json
     *
     * @param array(string) $metaFields
     */
    public static function setJsonMetaFields($metaFields) {
        self::$jsonMetaFields = $metaFields;
    }

    /**
     * Add meta field name that should be populated to json
     *
     * @param string $fieldName
     */
    public static function addJsonMetaField($fieldName){
        if(false === array_search($fieldName, self::$jsonMetaFields)){
            self::$jsonMetaFields[]=$fieldName;
        }
    }

    /**
     * Remove meta field name that should not be populated to json
     *
     * @param string $fieldName
     */
    public static function removeJsonMetaField($fieldName){
        $i = array_search($fieldName, self::$jsonMetaFields);
        if(false !== $i){
            self::$jsonMetaFields = array_splice(self::$jsonMetaFields, $i, 1);
        }
    }

    /**
     * Packs this user into assoc array for JSON representation.
     * Used for API Output
     *
     * @return array
     */
    public function packJsonItem() {
        $jsonItem = array();
        $jsonItem['id'] = (int)$this->getId();
        $jsonItem['user_login'] = $this->getLogin();
        $jsonItem['user_nicename'] = $this->getNicename();
        $jsonItem['user_url'] = $this->getUrl();
        $jsonItem['user_email'] = $this->getEmail();
        $jsonItem['display_name'] = $this->getDisplayName();
        $jsonItem['first_name'] = $this->getFirstName();
        $jsonItem['last_name'] = $this->getLastName();
        $jsonItem['description'] = $this->getDescription();
        $jsonItem['rich_editing'] = $this->getRichEditing();
        $jsonItem['user_registered'] = DateHelper::datetimeToJsonStr($this->getRegistered());
        $jsonItem['role'] = $this->getRole();
        $jsonItem['jabber'] = $this->getJabber();
        $jsonItem['aim'] = $this->getAim();
        $jsonItem['yim'] = $this->getYim();
        $jsonItem['profile_link'] = $this->getProfileLink();

        $meta = array(
        );
        foreach(self::$jsonMetaFields as $field){
            $meta[$field] = $this->getMeta($field);
        }
        if($meta){
            $reservedMeta = array(
                'first_name', 
                'last_name', 
                'description',
                'rich_editing', 
                'jabber',
                'aim',
                'yim',
            );
            foreach($reservedMeta as $key){
                if(isset($meta[$key])){
                    unset($meta[$key]);
                }
            }
            $adminMeta = array(
                'rich_editing',
                'comment_shortcuts',
                'admin_color',
                'use_ssl',
                'wp_capabilities',
                'wp_user_level',
                'default_password_nag',
                'show_admin_bar_front',
            );

            if(!AclHelper::isAdmin()){
                foreach($adminMeta as $key){
                    if(isset($meta[$key])){
                        unset($meta[$key]);
                    }
                }
            }
            foreach ($meta as $key=>$value){
                if(strpos($key, 'wp_')===0 || is_serialized($meta[$key])){
                    unset($meta[$key]);
                }
            }
            $jsonItem['meta']=$meta;
        }
        
        return $jsonItem;
    }

    /**
     * Get validation errors after unpacking from request input
     * Should be set by validateInput
     *
     * @return array[field]='Error Text'
     */
    public static function getValidationErrors() {
        return self::$validationErrors;
    }

	/**
	 * Add validation errors after unpacking from request input
	 *
	 * @param array[field]='Error Text' $errors
	 */
	public static function addValidationErrors($errors) {
		static::$validationErrors = array_merge(static::$validationErrors, $errors);
	}

    /**
     * Unpacks request input.
     * Used by REST Controllers.
     *
     * @param array $input
     * @return PostModel
     */
    public static function unpackJsonItem($input = array()) {
        if(empty($input)){
            $input = InputHelper::getParams();
        }
	    $id = Util::getItem($input, 'id', 0);

	    $obj = $id? static::selectById($id): new static();

	    $valid = static::validateInput($input, $id? $obj:null);

	    if($valid) {
		    $input = array_merge( $obj->packJsonItem(), $input );

		    $obj->setId( Util::getItem( $input, 'id', 0 ) );
		    $obj->setLogin( Util::getItem( $input, 'user_login' ) );
		    $obj->setEmail( Util::getItem( $input, 'user_email' ) );
		    $obj->setNicename( Util::getItem( $input, 'user_nicename' ) );
		    $obj->setUrl( Util::getItem( $input, 'user_url' ) );
		    $obj->setDisplayName( Util::getItem( $input, 'display_name' ) );
//        $obj->setNickname(Util::getItem($input, 'nickname'));
		    $obj->setFirstName( Util::getItem( $input, 'first_name' ) );
		    $obj->setLastName( Util::getItem( $input, 'last_name' ) );
		    $obj->setDescription( Util::getItem( $input, 'description' ) );
		    $obj->setRichEditing( Util::getItem( $input, 'rich_editing' ) );
		    $obj->setRegistered( DateHelper::jsonStrToDatetime( Util::getItem( $input, 'user_registered' ) ) );
		    $obj->setRole( Util::getItem( $input, 'role' ) );
		    $obj->setJabber( Util::getItem( $input, 'jabber' ) );
		    $obj->setAim( Util::getItem( $input, 'aim' ) );
		    $obj->setYim( Util::getItem( $input, 'yim' ) );

		    $adminMeta    = array(
			    'rich_editing',
			    'comment_shortcuts',
			    'admin_color',
			    'use_ssl',
			    'wp_capabilities',
			    'wp_user_level',
			    'default_password_nag',
			    'show_admin_bar_front',
		    );
		    $reservedMeta = array(
			    'first_name',
			    'last_name',
			    'description',
			    'rich_editing',
			    'jabber',
			    'aim',
			    'yim',
		    );

		    $meta = InputHelper::getParam( 'meta' );
		    if ( ! AclHelper::isAdmin() && $meta && is_array( $meta ) ) {
			    foreach ( $adminMeta as $key ) {
				    if ( isset( $meta[ $key ] ) ) {
					    unset( $meta[ $key ] );
				    }
			    }
		    }
		    foreach ( $reservedMeta as $key ) {
			    if ( isset( $meta[ $key ] ) ) {
				    unset( $meta[ $key ] );
			    }
		    }
		    if ( isset( $meta ) && is_array( $meta ) ) {
			    foreach ( $meta as $key => $value ) {
				    if ( strpos( $key, 'wp_' ) === 0 ) {
					    unset( $meta[ $key ] );
				    }
			    }
		    }
		    InputHelper::setParam( 'meta', $meta );
		    return $obj;
	    }

	    return null;
    }

	/**
	 * Validates input and sets $validationErrors
	 *
	 * @param array $input
	 * @param UserModel $oldState
	 *
	 * @return bool is input valid
	 */
    public static function validateInput($input = array(), $oldState = null) {
	    self::$validationErrors = array();
        $valid = apply_filters('UserModel.validateInput', true, $input, $oldState);
        return $valid;
    }
    
    /**
     * TODO: revise this function
     * returns true if the user is administrator
     *
     * @return boolean
     */
    public function isAdmin() {
        if (is_multisite()) {
            $super_admins = get_super_admins();
            if (is_array($super_admins) && in_array($this->getLogin(), $super_admins))
                return true;
        } elseif($this->getWpUser()) {
            if ($this->getWpUser()->has_cap('delete_users'))
                return true;
        }
        
        return false;
    }

    /**
     * Flush user cache
     */
    public static function flushCache(){
        self::$userCacheByEmail = array();
        self::$userCacheByLogin = array();
        self::$userCacheById = array();
    }

    /**
     * Get cached user by id
     *
     * @param int $id
     * @return mixed|null
     */
    public static function getUserCacheById($id = 0){
        if($id){
            return Util::getItem(self::$userCacheById, $id);
        }
        return null;
    }

    /**
     * Get cached user by email
     *
     * @param string $email
     * @return array
     */
    public static function getUserCacheByEmail($email = ''){
        if($email){
            $id = Util::getItem(self::$userCacheByEmail, $email);
            return Util::getItem(self::$userCacheById, $id, null);
        }
        $ret = array();
        foreach(self::$userCacheByEmail as $email => $id){
            $item = Util::getItem(self::$userCacheById, $id);
            if($item){
                $ret[$email] = $item;
            }
        }
        return $ret;
    }

    /**
     * Get cached user by email
     *
     * @param string $login
     * @return array
     */
    public static function getUserCacheByLogin($login = ''){
        if($login){
            $id = Util::getItem(self::$userCacheByLogin, $login);
            return Util::getItem(self::$userCacheById, $id, null);
        }
        $ret = array();
            foreach(self::$userCacheByLogin as $login => $id){
                $item = Util::getItem(self::$userCacheById, $id);
                if($item){
                    $ret[$login] = $item;
                }
            }
        return $ret;
    }

}
