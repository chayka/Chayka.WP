Chayka\WP\Helpers\DbHelper
===============

Class DbHelper contains set of handy database methods.




* Class name: DbHelper
* Namespace: Chayka\WP\Helpers







Methods
-------


### wpdb

    \wpdb Chayka\WP\Helpers\DbHelper::wpdb()

Get global $wpdb



* Visibility: **public**
* This method is **static**.




### dbInstall

    mixed Chayka\WP\Helpers\DbHelper::dbInstall(string $currentVersion, string $versionOptionName, string $sqlPath, array $versionHistory)

Perform db installation scripts for the Plugin / Theme



* Visibility: **public**
* This method is **static**.


#### Arguments
* $currentVersion **string**
* $versionOptionName **string** - &lt;p&gt;wp option where current version is stored&lt;/p&gt;
* $sqlPath **string** - &lt;p&gt;dir name where all update/installation sql/scripts are stored&lt;/p&gt;
* $versionHistory **array** - &lt;p&gt;array that contains all the versions of db structure (e.g. array(&#039;1.0&#039;, &#039;1.1&#039;))&lt;/p&gt;



### dbUpdate

    mixed Chayka\WP\Helpers\DbHelper::dbUpdate(string $currentVersion, string $versionOptionName, string $sqlPath, array $versionHistory)

Perform db update scripts for the Plugin / Theme



* Visibility: **public**
* This method is **static**.


#### Arguments
* $currentVersion **string**
* $versionOptionName **string** - &lt;p&gt;wp option where current version is stored&lt;/p&gt;
* $sqlPath **string** - &lt;p&gt;dir name where all update/installation sql/scripts are stored&lt;/p&gt;
* $versionHistory **array** - &lt;p&gt;array that contains all the versions of db structure (e.g. array(&#039;1.0&#039;, &#039;1.1&#039;))&lt;/p&gt;



### dbTable

    string Chayka\WP\Helpers\DbHelper::dbTable(string $table)

Get wp instance prefixed table name



* Visibility: **public**
* This method is **static**.


#### Arguments
* $table **string**



### insert

    integer Chayka\WP\Helpers\DbHelper::insert(array|object|\Chayka\WP\Helpers\DbReady $data, null $table)

Insert provided data to a table.

If $data is DbReady, omit other params.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array|object|[array](Chayka-WP-Helpers-DbReady.md)**
* $table **null**



### update

    boolean Chayka\WP\Helpers\DbHelper::update(array|object|\Chayka\WP\Helpers\DbReady $data, string $table, array $where)

Update provided row in a table.

If $data is DbReady, omit other params.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $data **array|object|[array](Chayka-WP-Helpers-DbReady.md)**
* $table **string**
* $where **array**



### delete

    mixed Chayka\WP\Helpers\DbHelper::delete($table, string $key, integer $value, string $format)

Delete a row from a table.

If $table is DbReady, omit other params.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $table **mixed**
* $key **string**
* $value **integer**
* $format **string**



### selectSql

    array Chayka\WP\Helpers\DbHelper::selectSql($sql, \Chayka\WP\Helpers\string/DbReady $className)

Select several rows using sql.

If DbReady $className provided, then raw data will be unpacked.
You can use {table} placeholder, that will be replaced with $className::getDbTable()

* Visibility: **public**
* This method is **static**.


#### Arguments
* $sql **mixed**
* $className **Chayka\WP\Helpers\string/DbReady**



### selectSqlLimitOffset

    array Chayka\WP\Helpers\DbHelper::selectSqlLimitOffset(string $sql, integer $limit, integer $offset, null|string $className)

Limited sql select.

Adds LIMIT $limit OFFSET $offset to $sql.
Ensures SELECT SQL_CALC_FOUND_ROWS

http://habrahabr.ru/post/217521/ -> join optimization

* Visibility: **public**
* This method is **static**.


#### Arguments
* $sql **string**
* $limit **integer**
* $offset **integer**
* $className **null|string**



### selectSqlPage

    array Chayka\WP\Helpers\DbHelper::selectSqlPage($sql, integer $page, integer $perPage, null|string $className)

Limited sql select.

Adds LIMIT $perPage OFFSET ($page - 1) * $perPage to $sql.
Ensures SELECT SQL_CALC_FOUND_ROWS

* Visibility: **public**
* This method is **static**.


#### Arguments
* $sql **mixed**
* $page **integer**
* $perPage **integer**
* $className **null|string**



### selectSqlRow

    mixed|null Chayka\WP\Helpers\DbHelper::selectSqlRow($sql, null $className)

Select a single row, using sql query



* Visibility: **public**
* This method is **static**.


#### Arguments
* $sql **mixed**
* $className **null**



### selectSqlColumn

    mixed|null Chayka\WP\Helpers\DbHelper::selectSqlColumn($sql)

Select a single column, using sql query



* Visibility: **public**
* This method is **static**.


#### Arguments
* $sql **mixed**



### selectSqlValue

    mixed|null Chayka\WP\Helpers\DbHelper::selectSqlValue($sql)

Select a single cell value, using sql query



* Visibility: **public**
* This method is **static**.


#### Arguments
* $sql **mixed**



### rowsFound

    integer Chayka\WP\Helpers\DbHelper::rowsFound()

Get the number of rows found during last sql query.

'SELECT FOUND_ROWS()' is utiliezed.

* Visibility: **public**
* This method is **static**.




### selectById

    \Chayka\WP\Helpers\DbReady|null Chayka\WP\Helpers\DbHelper::selectById($id, $class, string $format)

Select DbReady object from db by $id



* Visibility: **public**
* This method is **static**.


#### Arguments
* $id **mixed**
* $class **mixed**
* $format **string**



### selectBy

    \Chayka\WP\Helpers\DbReady|array|null Chayka\WP\Helpers\DbHelper::selectBy($param, $value, $class, boolean $multiple, string $format)

Select DbReady object from db by some unique key.

Use unique-indexed column.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $param **mixed**
* $value **mixed**
* $class **mixed**
* $multiple **boolean**
* $format **string**



### prepare

    mixed Chayka\WP\Helpers\DbHelper::prepare($sql)

Prepare sql query.

Alias of $wpdb->prepare(), provide substituted params after $sql query.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $sql **mixed**



### query

    false|integer Chayka\WP\Helpers\DbHelper::query($sql)

Perform sql query, can pass arguments for placeholders.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $sql **mixed**


