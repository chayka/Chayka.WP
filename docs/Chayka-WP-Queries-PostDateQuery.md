Chayka\WP\Queries\PostDateQuery
===============

Class PostDateQuery is a helper for building date queries inside WP_Query




* Class name: PostDateQuery
* Namespace: Chayka\WP\Queries





Properties
----------


### $postQuery

    protected \Chayka\WP\Queries\PostQuery $postQuery = null

PostQuery that will be patched using this date query



* Visibility: **protected**


### $vars

    protected array $vars = array()

An array that holds the date query params



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\WP\Queries\PostDateQuery::__construct($postQuery)

PostDateQuery constructor



* Visibility: **public**


#### Arguments
* $postQuery **mixed**



### getVars

    array Chayka\WP\Queries\PostDateQuery::getVars()

Get all vars



* Visibility: **public**




### setVar

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::setVar(string $key, string $value)

Set query filter var



* Visibility: **public**


#### Arguments
* $key **string**
* $value **string**



### year

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::year(integer $value)

Set year comparison value



* Visibility: **public**


#### Arguments
* $value **integer**



### month

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::month(integer $value)

Set month comparison value



* Visibility: **public**


#### Arguments
* $value **integer**



### week

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::week(integer $value)

Set week comparison value



* Visibility: **public**


#### Arguments
* $value **integer**



### day

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::day(integer $value)

Set day comparison value



* Visibility: **public**


#### Arguments
* $value **integer**



### hour

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::hour(integer $value)

Set hour comparison value



* Visibility: **public**


#### Arguments
* $value **integer**



### minute

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::minute(integer $value)

Set minute comparison value



* Visibility: **public**


#### Arguments
* $value **integer**



### second

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::second(integer $value)

Set second comparison value



* Visibility: **public**


#### Arguments
* $value **integer**



### after

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::after(integer $year, integer $month, integer $day)

Set after comparison value



* Visibility: **public**


#### Arguments
* $year **integer** - &lt;ul&gt;
&lt;li&gt;year or string strtotime()-compatible&lt;/li&gt;
&lt;/ul&gt;
* $month **integer**
* $day **integer**



### before

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::before(integer $year, integer $month, integer $day)

Set before comparison value



* Visibility: **public**


#### Arguments
* $year **integer** - &lt;ul&gt;
&lt;li&gt;year or string strtotime()-compatible&lt;/li&gt;
&lt;/ul&gt;
* $month **integer**
* $day **integer**



### inclusive

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::inclusive(boolean $value)

Set inclusive comparison flag



* Visibility: **public**


#### Arguments
* $value **boolean**



### column

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::column(string $value)

Set Comparison Column name



* Visibility: **public**


#### Arguments
* $value **string**



### compare

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::compare(string $value)

Set Comparison type



* Visibility: **public**


#### Arguments
* $value **string** - &lt;p&gt;&#039;=&#039;, &#039;!=&#039;, &#039;&gt;&#039;, &#039;&gt;=&#039;, &#039;&lt;&#039;, &#039;&lt;=&#039;, &#039;IN&#039;, &#039;NOT IN&#039;, &#039;BETWEEN&#039;, &#039;NOT BETWEEN&#039;&lt;/p&gt;



### relation

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostDateQuery::relation(string $value)

Set Comparison type



* Visibility: **public**


#### Arguments
* $value **string** - &lt;p&gt;&#039;=&#039;, &#039;!=&#039;, &#039;&gt;&#039;, &#039;&gt;=&#039;, &#039;&lt;&#039;, &#039;&lt;=&#039;, &#039;IN&#039;, &#039;NOT IN&#039;, &#039;BETWEEN&#039;, &#039;NOT BETWEEN&#039;&lt;/p&gt;



### push

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostDateQuery::push()

Push date query to post query and return post query



* Visibility: **public**




### next

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostDateQuery::next()

Push date query to post query and return new date query



* Visibility: **public**




### pushAnd

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostDateQuery::pushAnd()

Push date query to post query and return new date query



* Visibility: **public**




### pushOr

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostDateQuery::pushOr()

Push date query to post query and return new date query



* Visibility: **public**



