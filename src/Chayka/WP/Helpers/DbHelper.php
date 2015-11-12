<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP\Helpers;

use wpdb;

/**
 * Class DbHelper contains set of handy database methods.
 *
 * @package Chayka\WP\Helpers
 */
class DbHelper {

    /**
     * Get global $wpdb
     *
     * @return wpdb
     */
    public static function wpdb(){
        global $wpdb;
        return $wpdb;
    }

    /**
     * Perform db installation scripts for the Plugin / Theme
     *
     * @param string $currentVersion
     * @param string $versionOptionName wp option where current version is stored
     * @param string $sqlPath dir name where all update/installation sql/scripts are stored
     * @param array $versionHistory array that contains all the versions of db structure (e.g. array('1.0', '1.1'))
     */
    public static function dbInstall($currentVersion, $versionOptionName, $sqlPath, $versionHistory = array('1.0')) {
        global $wpdb;
        $installedVer = get_option($versionOptionName);
        $queries = array();
        if(!$installedVer){
            $filename = $sqlPath.'/install.'.$currentVersion.'.sql';
            if(file_exists($filename)){
                $cnt = file_get_contents($filename);
                $tmp = preg_split('%;\s*%m', $cnt);
                foreach($tmp as $query){
                    $queries[] = str_replace('{prefix}', $wpdb->prefix, $query);
                }
            }
        }elseif ($installedVer != $currentVersion){
            $found = false;
            foreach ($versionHistory as $ver){
                if($found){
                    $filename = $sqlPath.'/update.'.$ver.'.sql';
                    if(file_exists($filename)){
                        $cnt = file_get_contents($sqlPath.'/update.'.$ver.'.sql');
                        $tmp = preg_split('%;\s*%m', $cnt);
                        foreach($tmp as $query){
                            $queries[] = str_replace('{prefix}', $wpdb->prefix, $query);
                        }
                    }
                }
                if(!$found && $ver==$installedVer){
                    $found = true;
                }
            }
        }
        
        foreach($queries as $query){
            self::wpdb()->query($query);
        }
        
        update_option($versionOptionName, $currentVersion);
    }

    /**
     * Perform db update scripts for the Plugin / Theme
     *
     * @param string $currentVersion
     * @param string $versionOptionName wp option where current version is stored
     * @param string $sqlPath dir name where all update/installation sql/scripts are stored
     * @param array $versionHistory array that contains all the versions of db structure (e.g. array('1.0', '1.1'))
     */
    public static function dbUpdate($currentVersion, $versionOptionName, $sqlPath, $versionHistory = array('1.0')) {
        if (get_option($versionOptionName) != $currentVersion) {
            self::dbInstall($currentVersion, $versionOptionName, $sqlPath, $versionHistory);
        }
    }

    /**
     * Get wp instance prefixed table name
     *
     * @param string $table
     * @return string
     */
    public static function dbTable($table){
        global $wpdb;
        return $wpdb->prefix.$table;
    }

    /**
     * Insert provided data to a table.
     * If $data is DbReady, omit other params.
     *
     * @param array|object|DbReady $data
     * @param null $table
     * @return int
     */
    public static function insert($data, $table=null) {
        $wpdb = self::wpdb();
        if($data instanceof DbReady){
            if(!$table){
                $class = get_class($data);
                $table = call_user_func(array($class, 'getDbTable'));
            }
            $data = $data->packDbRecord(false);
        }elseif(!is_array($data)){
            $data = get_object_vars($data);
        }
        $id = $wpdb->insert($table, $data)?$wpdb->insert_id:0;
        return $id;
    }

    /**
     * Update provided row in a table.
     * If $data is DbReady, omit other params.
     *
     * @param array|object|DbReady $data
     * @param string $table
     * @param array $where
     * @return bool
     */
    public static function update($data, $table = '', $where = array()) {
        $wpdb = self::wpdb();
        if($data instanceof DbReady){
            $class = get_class($data);
            if(!$table){
                $table = call_user_func(array($class, 'getDbTable'));
            }
            if(empty($where)){
                $key = call_user_func(array($class, 'getDbIdColumn'));
                $where[$key] = $data->getId();
            }
            $data = $data->packDbRecord(true);
        }elseif(!is_array($data)){
            $data = get_object_vars($data);
        }
        $res = $wpdb->update($table, $data, $where);
        return !($res===false);
    }

    /**
     * Delete a row from a table.
     * If $table is DbReady, omit other params.
     *
     * @param $table
     * @param string $key
     * @param int $value
     * @param string $format
     * @return mixed
     */
    public static function delete($table, $key = '', $value = 0, $format = '%d') {
        if($table instanceof DbReady){
            $class = get_class($table);
            $value = $table->getId();
            $key = call_user_func(array($class, 'getDbIdColumn'));
            $table = call_user_func(array($class, 'getDbTable'));
        }
        $wpdb = self::wpdb();
        return $wpdb->query(
                    $wpdb->prepare("
                        DELETE FROM $table
                        WHERE $key = $format",
                        $value
                    )
                );
    }

    /**
     * Select several rows using sql.
     * If DbReady $className provided, then raw data will be unpacked.
     * You can use {table} placeholder, that will be replaced with $className::getDbTable()
     *
     * @param $sql
     * @param string/DbReady $className
     * @return array
     */
    public static function selectSql($sql, $className = null){
        $wpdb = self::wpdb();
        if($className) {
            if (is_object($className)) {
                $className = get_class($className);
            }
            $table = call_user_func(array($className, 'getDbTable'));

            $sql = str_replace('{table}', $table, $sql);
        }
        $dbRecords = $wpdb->get_results($sql);
        if($className) {
            foreach ($dbRecords as $i=>$dbRecord) {
                $dbRecords[$i] = call_user_func(array($className, 'unpackDbRecord'), $dbRecord);
            }
        }

        return $dbRecords;
    }

	/**
	 * Limited sql select.
	 * Adds LIMIT $limit OFFSET $offset to $sql.
	 * Ensures SELECT SQL_CALC_FOUND_ROWS
	 *
	 * http://habrahabr.ru/post/217521/ -> join optimization
	 *
	 * @param string $sql
	 * @param int $limit
	 * @param int $offset
	 * @param null|string $className
	 *
	 * @return array
	 */
	public static function selectSqlLimitOffset($sql, $limit = 0, $offset = 0, $className = null){
		if(!preg_match('%^\s*SELECT\s+SQL_CALC_FOUND_ROWS\s%im', $sql)){
			$sql = preg_replace('%\s*SELECT%im', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
		}
		$sql.=sprintf(' LIMIT %d OFFSET %d', $limit, $offset);
		return self::selectSql($sql, $className);
	}

	/**
	 * Limited sql select.
	 * Adds LIMIT $perPage OFFSET ($page - 1) * $perPage to $sql.
	 * Ensures SELECT SQL_CALC_FOUND_ROWS
	 *
	 * @param $sql
	 * @param int $page
	 * @param int $perPage
	 * @param null|string $className
	 *
	 * @return array
	 */
	public static function selectSqlPage($sql, $page = 1, $perPage = 10, $className = null){
		if($page < 1){
			$page = 1;
		}
		$offset = ($page - 1) * $perPage;
		return self::selectSqlLimitOffset($sql, $perPage, $offset, $className);
	}

    /**
     * Select a single row, using sql query
     *
     * @param $sql
     * @param null $className
     *
     * @return mixed|null
     */
    public static function selectSqlRow($sql, $className = null){
        $data = self::selectSql($sql, $className);
        if(!empty($data)){
            return reset($data);
        }
        return null;
    }

    /**
     * Select a single column, using sql query
     *
     * @param $sql
     *
     * @return mixed|null
     */
    public static function selectSqlColumn($sql){
        $data = self::selectSql($sql);
        if(!empty($data)){
            $result = [];
            foreach($data as $row){
                if(is_object($row)){
                    $row = get_object_vars($row);
                }
                $result[]=reset($row);
            }

            return $result;
        }
        return null;
    }

    /**
     * Select a single cell value, using sql query
     *
     * @param $sql
     *
     * @return mixed|null
     */
    public static function selectSqlValue($sql){
        $data = self::selectSql($sql);
        if(!empty($data)){
            $row = reset($data);
            if(is_object($row)){
                $row = get_object_vars($row);
            }
            return reset($row);
        }
        return null;
    }

    /**
     * Get the number of rows found during last sql query.
     * 'SELECT FOUND_ROWS()' is utiliezed.
     *
     * @return integer
     */
    public static function rowsFound(){
        return self::wpdb()->get_var('SELECT FOUND_ROWS()');
    }

    /**
     * Select DbReady object from db by $id
     *
     * @param $id
     * @param $class
     * @param string $format
     * @return DbReady|null
     */
    public static function selectById($id, $class, $format = '%d'){
        $wpdb = self::wpdb();
        $table = call_user_func(array($class, 'getDbTable'));
        $key = call_user_func(array($class, 'getDbIdColumn'));
        $sql = $wpdb->prepare("SELECT * FROM $table WHERE $key = $format", $id);
        $dbRecord = $wpdb->get_row($sql);
        return $dbRecord?call_user_func(array($class, 'unpackDbRecord'), $dbRecord):null;
    }

    /**
     * Select DbReady object from db by some unique key.
     * Use unique-indexed column.
     *
     * @param $param
     * @param $value
     * @param $class
     * @param bool $multiple
     * @param string $format
     * @return DbReady|array|null
     */
    public static function selectBy($param, $value, $class, $multiple = false, $format = '%s'){
        $wpdb = self::wpdb();
        $table = call_user_func(array($class, 'getDbTable'));
        $sql = $wpdb->prepare("SELECT * FROM $table WHERE $param = $format", $value);
        if($multiple){
            $dbRecords = $wpdb->get_results($sql);
            if($class) {
                foreach ($dbRecords as $i=>$dbRecord) {
                    $dbRecords[$i] = call_user_func(array($class, 'unpackDbRecord'), $dbRecord);
                }
            }
            return $dbRecords;
        }
        $dbRecord = $wpdb->get_row($sql);
        return $dbRecord?call_user_func(array($class, 'unpackDbRecord'), $dbRecord):null;
    }

    /**
     * Prepare sql query.
     * Alias of $wpdb->prepare(), provide substituted params after $sql query.
     *
     * @param $sql
     * @return mixed
     */
    public static function prepare($sql){
        global $wpdb;
        
        $args = func_get_args();

        return call_user_func_array(array($wpdb, 'prepare'), $args);
    }

    /**
     * Perform sql query, can pass arguments for placeholders.
     *
     * @param $sql
     *
     * @return false|int
     */
    public static function query($sql){
        global $wpdb;

        $args = func_get_args();

        $sql = call_user_func_array(array($wpdb, 'prepare'), $args);

        return $wpdb->query($sql);
    }
}

