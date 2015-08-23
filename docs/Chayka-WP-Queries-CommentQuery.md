Chayka\WP\Queries\CommentQuery
===============

Class CommentQuery is a helper that allows to build $arguments array
for WP_Comments_Query
For more details see https://codex.wordpress.org/Class_Reference/WP_Comment_Query




* Class name: CommentQuery
* Namespace: Chayka\WP\Queries





Properties
----------


### $vars

    protected array $vars = array()

Holds an array that is formed using helper methods and passed to WP_Comments_Query()
to fetch comments form DB.



* Visibility: **protected**


### $post

    protected \Chayka\WP\Models\PostModel $post = null

Post to fetch comments for



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\WP\Queries\CommentQuery::__construct()

Constructor method, does nothing for now.



* Visibility: **public**




### getVars

    array Chayka\WP\Queries\CommentQuery::getVars()

Get all the set vars in an assoc array



* Visibility: **public**




### setVars

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::setVars(array $vars)

Add vars to the set



* Visibility: **public**


#### Arguments
* $vars **array**



### setVar

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::setVar(string $key, string $value)

Set query filter var



* Visibility: **public**


#### Arguments
* $key **string**
* $value **string**



### query

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::query(integer|\Chayka\WP\Models\PostModel $post)

Create instance of query object



* Visibility: **public**
* This method is **static**.


#### Arguments
* $post **integer|[integer](Chayka-WP-Models-PostModel.md)** - &lt;p&gt;postId or post itself&lt;/p&gt;



### select

    \Chayka\WP\Queries\array(CommentModel) Chayka\WP\Queries\CommentQuery::select()

Select all matching comments



* Visibility: **public**




### selectOne

    \Chayka\WP\Models\CommentModel Chayka\WP\Queries\CommentQuery::selectOne()

Select firs matching comment



* Visibility: **public**




### selectCount

    integer Chayka\WP\Queries\CommentQuery::selectCount(boolean $omitLimitOffset)

Select total amount of comments under this query instead of comments



* Visibility: **public**


#### Arguments
* $omitLimitOffset **boolean**



### status

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::status(string $status)

Only return comments with this status.



* Visibility: **public**


#### Arguments
* $status **string**



### status_Hold

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::status_Hold()

Only return comments with status 'hold'.



* Visibility: **public**




### status_Approve

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::status_Approve()

Only return comments with status 'approve'.



* Visibility: **public**




### status_Spam

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::status_Spam()

Only return comments with status 'spam'.



* Visibility: **public**




### status_Trash

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::status_Trash()

Only return comments with status 'trash'.



* Visibility: **public**




### number

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::number(integer $number)

Number of comments to return. Leave blank to return all comments.



* Visibility: **public**


#### Arguments
* $number **integer**



### offset

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::offset(integer $offset)

Offset from latest comment. You must include $number along with this.



* Visibility: **public**


#### Arguments
* $offset **integer**



### order

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::order(string $order)

How to sort by the field specified using orderBy().

Valid values: ASC, DESC
Default: DESC

* Visibility: **public**


#### Arguments
* $order **string**



### order_ASC

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::order_ASC()

Sort in ascending order by the field specified using orderBy().



* Visibility: **public**




### order_DESC

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::order_DESC()

Sort in descending order by the field specified using orderBy().



* Visibility: **public**




### orderBy

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::orderBy(string $orderBy)

Set the field used to sort comments.

Default: comment_date_gmt

* Visibility: **public**


#### Arguments
* $orderBy **string**



### postId

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::postId(integer $postId)

Only return comments for a particular post or page.



* Visibility: **public**


#### Arguments
* $postId **integer**



### post

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::post(\Chayka\WP\Models\PostModel $post)

Only return comments for a particular post.



* Visibility: **public**


#### Arguments
* $post **[Chayka\WP\Models\PostModel](Chayka-WP-Models-PostModel.md)**



### postIdIn

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::postIdIn(array|string $ids)

Select comments for specified post ids



* Visibility: **public**


#### Arguments
* $ids **array|string**



### postIdNotIn

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::postIdNotIn(array|string $ids)

Exclude comments for specified post ids



* Visibility: **public**


#### Arguments
* $ids **array|string**



### commentId

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::commentId(integer $commentId)

Select comment by is.



* Visibility: **public**


#### Arguments
* $commentId **integer**



### commentIdIn

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::commentIdIn(array|string $ids)

Select comments for specified ids



* Visibility: **public**


#### Arguments
* $ids **array|string**



### commentIdNotIn

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::commentIdNotIn(array|string $ids)

Exclude comments for specified ids



* Visibility: **public**


#### Arguments
* $ids **array|string**



### userId

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::userId(integer $userId)

Only return comments for a particular user.



* Visibility: **public**


#### Arguments
* $userId **integer**



### userIdIn

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::userIdIn(array|string $ids)

Select comments for specified user ids



* Visibility: **public**


#### Arguments
* $ids **array|string**



### userIdNotIn

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::userIdNotIn(array|string $ids)

Exclude comments for specified user ids



* Visibility: **public**


#### Arguments
* $ids **array|string**



### parent

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::parent($commentId)

Select replies for specified id



* Visibility: **public**


#### Arguments
* $commentId **mixed**



### authorEmail

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::authorEmail(string $email)

Search by author email



* Visibility: **public**


#### Arguments
* $email **string**



### postUserId

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::postUserId(integer $userId)

Only return comments for a particular post author.



* Visibility: **public**


#### Arguments
* $userId **integer**



### postUserIdIn

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::postUserIdIn(array|string $ids)

Select comments for specified post author ids



* Visibility: **public**


#### Arguments
* $ids **array|string**



### postUserIdNotIn

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::postUserIdNotIn(array|string $ids)

Exclude comments for specified post author ids



* Visibility: **public**


#### Arguments
* $ids **array|string**



### includeUnapproved

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::includeUnapproved(array|string $include)

Array of IDs or email addresses of users whose unapproved comments
will be returned by the query regardless of `$status`. Default empty.



* Visibility: **public**


#### Arguments
* $include **array|string**



### fields

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\CommentQuery::fields(string|\Chayka\WP\Queries\array(string) $fields)

Set return values.



* Visibility: **public**


#### Arguments
* $fields **string|Chayka\WP\Queries\array(string)**



### fields_All

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\CommentQuery::fields_All()

Setup to return all fields



* Visibility: **public**




### fields_Ids

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\CommentQuery::fields_Ids()

Setup to return ids only



* Visibility: **public**




### returnCountOnly

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::returnCountOnly(boolean $countOnly)

Only return the total count of comments.



* Visibility: **public**


#### Arguments
* $countOnly **boolean**



### metaKey

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::metaKey(string $key)

Custom field key.



* Visibility: **public**


#### Arguments
* $key **string**



### metaValue

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::metaValue(string $value)

Custom field value



* Visibility: **public**


#### Arguments
* $value **string**



### metaValueNum

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::metaValueNum(\Chayka\WP\Queries\number $value)

Custom field numeric value



* Visibility: **public**


#### Arguments
* $value **Chayka\WP\Queries\number**



### metaCompare

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::metaCompare(string $compare)

Operator to test the 'meta_value'.

Possible values are '!=', '>', '>=', '<', or '<='. Default value is '='.

* Visibility: **public**


#### Arguments
* $compare **string**



### metaQuery

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::metaQuery(string $key, string|array $value, string $compare, string $type)

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

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::metaQueryRelation(string $relation)

Set relation for multiple meta_query handling
Should come first before metaQuery() call



* Visibility: **public**


#### Arguments
* $relation **string**



### metaQueryRelation_AND

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::metaQueryRelation_AND()

Set 'AND' relation for multiple meta_query handling
Should come first before metaQuery() call



* Visibility: **public**




### metaQueryRelation_OR

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Queries\CommentQuery::metaQueryRelation_OR()

Set 'OR' relation for multiple meta_query handling
Should come first before metaQuery() call



* Visibility: **public**



