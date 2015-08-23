<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Models;

use Chayka\Helpers\InputReady;
use Chayka\Helpers\JsonReady;
use Chayka\WP\Helpers\AclReady;
use Chayka\WP\Helpers\DbHelper;
use Chayka\WP\Helpers\DbReady;

/**
 * ReadyModel is a base class for any custom model you would like to create
 *
 * @package Chayka\WP\Models
 */
abstract class ReadyModel implements DbReady, JsonReady, InputReady, AclReady{

    /**
     * Array of validation errors, part of InputReady interface implementation
     *
     * @var array
     */
	protected static $validationErrors = array();

    /**
     * Model id
     *
     * @var int
     */
    protected $id;


    /**
     * Insert current instance to db and return object id
     *
     * @return integer
     */
    public function insert(){
        return $this->setId(DbHelper::insert($this))->getId();
    }

    /**
     * Update corresponding db row in db and return object id.
     *
     * @return integer
     */
    public function update(){
        return DbHelper::update($this);
    }

    /**
     * Delete corresponding db row from db.
     *
     * @return boolean
     */
    public function delete() {
        return DbHelper::delete($this);
    }

    /**
     * Delete instance from db by id
     *
     * @param $id
     * @return mixed
     */
    public static function deleteById($id) {
        return DbHelper::delete(static::getDbTable(), static::getDbIdColumn(), $id);
    }

    /**
     * Delete instance from db by some key
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public static function deleteBy($key, $value) {
        return DbHelper::delete(static::getDbTable(), $key, $value);
    }

    /**
     * Select instance from db by id.
     *
     * @param $id
     * @param bool $useCache
     * @return mixed
     */
    public static function selectById($id, $useCache = true){
        $obj = new static();
        return DbHelper::selectById($id, get_class($obj));
    }

    /**
     * Select instance from db by some key.
     * Mind to ensure index on $key field.
     *
     * @param $key
     * @param $value
     * @param bool $multiple
     * @param string $format
     * @return array|DbReady|null
     */
    public static function selectBy($key, $value, $multiple = false, $format = '%s'){
        $obj = new static();
        return DbHelper::selectBy($key, $value, get_class($obj), $multiple, $format);
    }

    /**
     * Select data using sql query.
     * You can use {table} placeholder, that will be replaced with self::getDbTable().
     * Accepts slq-prepare format (sql with placeholders and optional params as values)
     * @param $sql
     * @return array
     */
    public static function selectSql($sql){
        $args = func_get_args();

        if(count($args)>1){
            $sql = call_user_func_array(array('Chayka\\WP\\Helpers\\DbHelper', 'prepare'), $args);
        }

        return DbHelper::selectSql($sql, new static());
    }

	/**
	 * Select all entities
	 *
	 * @return array
	 */
	public static function selectAll(){
        return static::selectSql('SELECT SQL_CALC_FOUND_ROWS * FROM {table}');
    }

	/**
	 * Limited sql select.
	 * Adds LIMIT $limit OFFSET $offset to $sql.
	 *
	 * http://habrahabr.ru/post/217521/ -> join optimization
	 *
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return array
	 */
	public static function selectLimitOffset($limit = 0, $offset = 0){
		$sql=sprintf('SELECT SQL_CALC_FOUND_ROWS * FROM {table} LIMIT %d OFFSET %d', $limit, $offset);
		return self::selectSql($sql);
	}

	/**
	 * Limited sql select.
	 * Adds LIMIT $perPage OFFSET ($page - 1) * $perPage to $sql.
	 *
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return array
	 */
	public static function selectPage($page = 1, $perPage = 10){
		if($page < 1){
			$page = 1;
		}
		$offset = ($page - 1) * $perPage;
		return self::selectLimitOffset($perPage, $offset);
	}

    /**
     * Get id column name in db table
     *
     * @return mixed
     */
    public static function getDbIdColumn() {
        return 'id';
    }

    /**
     * Get instance id
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set instance id;
     * @param $id
     * @return $this
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

	/**
	 * Validates input and sets $validationErrors
	 *
	 * @param array $input
	 * @param mixed $oldState
	 *
	 * @return bool is input valid
	 */
	public static function validateInput($input = array(), $oldState = null) {
		static::$validationErrors = array();
		$valid = apply_filters(self::getDbTable().'.validateInput', true, $input, $oldState);
		return $valid;
	}

	/**
	 * Get validation errors after unpacking from request input
	 * Should be set by validateInput
	 *
	 * @return array[field]='Error Text'
	 */
	public static function getValidationErrors() {
		return static::$validationErrors;
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
	 * Checks user permission for privilege on current model.
	 * This function should be overriden to accept 'create', 'read', 'update', 'delete'.
	 * It should use parent::userCan() for convinience.
	 * It should apply_filters('<ModelName>.<privilege>', $model, $user);
	 *
	 * @param string $privilege
	 * @param null $user
	 *
	 * @return bool
	 */
	public function userCan( $privilege, $user = null ) {
		if(!$user){
			$user = UserModel::currentUser();
		}
		$userCan = true;
		$errors = array();

		$table = preg_replace('%^'.DbHelper::wpdb()->prefix.'%', '', static::getDbTable());

		$userCan = apply_filters($table.'.'.$privilege, $userCan, $this, $user);
		if(!$userCan){
			static::addValidationErrors($errors);
		}
		return $userCan;
	}

}