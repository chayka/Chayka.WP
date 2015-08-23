Chayka\WP\Queries\UserQuery
===============

Class UserQuery is a helper that allows to build $arguments array
for WP_User_Query
For more details see https://codex.wordpress.org/Class_Reference/WP_User_Query




* Class name: UserQuery
* Namespace: Chayka\WP\Queries





Properties
----------


### $vars

    protected array $vars = array()

Holds an array that is formed using helper methods and passed to WP_User_Query()
to fetch users form DB.



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\WP\Queries\UserQuery::__construct()

UserQuery constructor. Does nothing for now.



* Visibility: **public**




### getVars

    array Chayka\WP\Queries\UserQuery::getVars()

Get all vars



* Visibility: **public**




### setVars

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::setVars(array $vars)

Add vars to the set



* Visibility: **public**


#### Arguments
* $vars **array**



### setVar

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::setVar(string $key, string $value)

Set query filter var



* Visibility: **public**


#### Arguments
* $key **string**
* $value **string**



### query

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::query()

Create instance of query object



* Visibility: **public**
* This method is **static**.




### select

    \Chayka\WP\Queries\array(UserModel) Chayka\WP\Queries\UserQuery::select()

Select all matching users



* Visibility: **public**




### selectOne

    \Chayka\WP\Models\UserModel Chayka\WP\Queries\UserQuery::selectOne()

Select first matching user



* Visibility: **public**




### role

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::role(string $role)

Show users associated with certain role.



* Visibility: **public**


#### Arguments
* $role **string**



### role_Administrator

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::role_Administrator()

Show users associated with 'administrator' role.



* Visibility: **public**




### role_Editor

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::role_Editor()

Show users associated with 'editor' role.



* Visibility: **public**




### role_Author

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::role_Author()

Show users associated with 'author' role.



* Visibility: **public**




### role_Subscriber

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::role_Subscriber()

Show users associated with 'subscriber' role.



* Visibility: **public**




### includeUserIds

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::includeUserIds(string|\Chayka\WP\Queries\array(int) $userIds)

Show specific users.



* Visibility: **public**


#### Arguments
* $userIds **string|Chayka\WP\Queries\array(int)**



### excludeUserIds

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::excludeUserIds(string|\Chayka\WP\Queries\array(int) $userIds)

Show specific users.



* Visibility: **public**


#### Arguments
* $userIds **string|Chayka\WP\Queries\array(int)**



### blogId

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::blogId(integer $blogId)

Show users associated with certain blog on the network.



* Visibility: **public**


#### Arguments
* $blogId **integer**



### search

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::search(string $search, \Chayka\WP\Queries\array(string) $columns)

Searches for possible string matches on columns



* Visibility: **public**


#### Arguments
* $search **string** - &lt;p&gt;String to match&lt;/p&gt;
* $columns **Chayka\WP\Queries\array(string)** - &lt;p&gt;List of database table columns to matches the search string across multiple columns.&lt;/p&gt;



### searchColumns

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::searchColumns(array $columns)

Set list of database table columns to match the search string across multiple columns.



* Visibility: **public**


#### Arguments
* $columns **array**



### searchColumns_ID

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::searchColumns_ID()

Add 'ID' to the list of database table columns to match the search string across multiple columns.



* Visibility: **public**




### searchColumns_Login

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::searchColumns_Login()

Add 'user_login' to the list of database table columns to match the search string across multiple columns.



* Visibility: **public**




### searchColumns_Nicname

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::searchColumns_Nicname()

Add 'user_nicename' to the list of database table columns to match the search string across multiple columns.



* Visibility: **public**




### searchColumns_Email

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::searchColumns_Email()

Add 'user_email' to the list of database table columns to match the search string across multiple columns.



* Visibility: **public**




### searchColumns_Url

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::searchColumns_Url()

Add 'user_url' to the list of database table columns to match the search string across multiple columns.



* Visibility: **public**




### number

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::number(integer $number)

The maximum returned number of results (needed in pagination).



* Visibility: **public**


#### Arguments
* $number **integer**



### offset

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::offset(integer $offset)

Offset the returned results (needed in pagination).



* Visibility: **public**


#### Arguments
* $offset **integer**



### order

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::order(string $order)

Designates the ascending or descending order of the 'orderby' parameter.

Defaults to 'ASC'

* Visibility: **public**


#### Arguments
* $order **string**



### order_ASC

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::order_ASC()

Designates the ascending order of the 'orderby' parameter.



* Visibility: **public**




### order_DESC

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::order_DESC()

Designates the descending order of the 'orderby' parameter.



* Visibility: **public**




### orderBy

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy(string $orderBy)

Sort retrieved users by parameter. Defaults to 'login'.



* Visibility: **public**


#### Arguments
* $orderBy **string**



### orderBy_ID

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy_ID()

Sort retrieved users by ID



* Visibility: **public**




### orderBy_DisplayName

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy_DisplayName()

Sort retrieved users by display_name



* Visibility: **public**




### orderBy_Name

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy_Name()

Sort retrieved users by user_name



* Visibility: **public**




### orderBy_Login

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy_Login()

Sort retrieved users by user_login



* Visibility: **public**




### orderBy_Nicename

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy_Nicename()

Sort retrieved users by user_nicename



* Visibility: **public**




### orderBy_Email

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy_Email()

Sort retrieved users by user_email



* Visibility: **public**




### orderBy_Url

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy_Url()

Sort retrieved users by user_url



* Visibility: **public**




### orderBy_DateRegistered

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy_DateRegistered()

Sort retrieved users by user_registered



* Visibility: **public**




### orderBy_PostCount

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::orderBy_PostCount()

Sort retrieved users by post_count



* Visibility: **public**




### metaKey

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::metaKey(string $key)

Custom field key.



* Visibility: **public**


#### Arguments
* $key **string**



### metaValue

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::metaValue(string $value)

Custom field value



* Visibility: **public**


#### Arguments
* $value **string**



### metaValueNum

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::metaValueNum(\Chayka\WP\Queries\number $value)

Custom field numeric value



* Visibility: **public**


#### Arguments
* $value **Chayka\WP\Queries\number**



### metaCompare

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::metaCompare(string $compare)

Operator to test the 'meta_value'.

Possible values are '!=', '>', '>=', '<', or '<='. Default value is '='.

* Visibility: **public**


#### Arguments
* $compare **string**



### metaQuery

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::metaQuery(string $key, string|array $value, string $compare, string $type)

Custom field parameters (available with Version 3.5).



* Visibility: **public**


#### Arguments
* $key **string** - &lt;p&gt;Custom field key&lt;/p&gt;
* $value **string|array** - &lt;p&gt;Custom field value
(Note: Array support is limited to a compare value of
&#039;IN&#039;, &#039;NOT IN&#039;, &#039;BETWEEN&#039;, &#039;NOT BETWEEN&#039;, &#039;EXISTS&#039; or &#039;NOT EXISTS&#039;)&lt;/p&gt;
* $compare **string** - &lt;p&gt;Operator to test. Possible values are
&#039;=&#039;, &#039;!=&#039;, &#039;&gt;&#039;, &#039;&gt;=&#039;, &#039;&lt;&#039;, &#039;&lt;=&#039;, &#039;LIKE&#039;, &#039;NOT LIKE&#039;, &#039;IN&#039;, &#039;NOT IN&#039;,
&#039;BETWEEN&#039;, &#039;NOT BETWEEN&#039;, &#039;EXISTS&#039;, and &#039;NOT EXISTS&#039;.
Default value is &#039;=&#039;.&lt;/p&gt;
* $type **string** - &lt;p&gt;Custom field type. Possible values are
&#039;NUMERIC&#039;, &#039;BINARY&#039;, &#039;CHAR&#039;, &#039;DATE&#039;, &#039;DATETIME&#039;, &#039;DECIMAL&#039;, &#039;SIGNED&#039;,
&#039;TIME&#039;, &#039;UNSIGNED&#039;. Default value is &#039;CHAR&#039;.&lt;/p&gt;



### metaQueryRelation

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::metaQueryRelation(string $relation)

Set relation for multiple meta_query handling
Should come first before metaQuery() call



* Visibility: **public**


#### Arguments
* $relation **string**



### metaQueryRelation_AND

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::metaQueryRelation_AND()

Set 'AND' relation for multiple meta_query handling
Should come first before metaQuery() call



* Visibility: **public**




### metaQueryRelation_OR

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::metaQueryRelation_OR()

Set 'OR' relation for multiple meta_query handling
Should come first before metaQuery() call



* Visibility: **public**




### fields

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::fields(string|\Chayka\WP\Queries\array(string) $fields)

Set return values format.



* Visibility: **public**


#### Arguments
* $fields **string|Chayka\WP\Queries\array(string)**



### fields_All

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::fields_All()

Set return values format to all fields.



* Visibility: **public**




### fields_AllWithMeta

    \Chayka\WP\Queries\UserQuery Chayka\WP\Queries\UserQuery::fields_AllWithMeta()

Set return values format to all fields.

Codex remark: 'all_with_meta' currently returns the same fields as 'all'
which does not include user fields stored in wp_usermeta.
You must create a second query to get the user meta fields by ID
or use the __get PHP magic method to get the values of these fields.

* Visibility: **public**



