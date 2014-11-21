<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 08.11.14
 * Time: 12:19
 */

namespace Chayka\WP\Models;


use Chayka\Helpers\JsonReady;
use Chayka\WP\Helpers\DbHelper;
use Chayka\WP\Helpers\DbReady;

abstract class ReadyModel implements DbReady, JsonReady{

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
     * Select instance from db by some unique key.
     * Mind to ensure index on $key field.
     *
     * @param $key
     * @param $value
     * @param string $format
     * @return DbReady|null
     */
    public static function selectBy($key, $value, $format = '%s'){
        $obj = new static();
        return DbHelper::selectBy($key, $value, get_class($obj), $format);
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

    public static function selectAll(){
        return static::selectSql('SELECT SQL_CALC_FOUND_ROWS * FROM {table}');
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

}