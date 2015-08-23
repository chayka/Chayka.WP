Chayka\WP\Queries\PostQuery
===============

Class PostQuery is a helper that allows to build $arguments array
for WP_Query
For more details see https://codex.wordpress.org/Class_Reference/WP_Query




* Class name: PostQuery
* Namespace: Chayka\WP\Queries





Properties
----------


### $vars

    protected array $vars = array()

Holds an array that is formed using helper methods and passed to WP_Query()
to fetch posts form DB.



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\WP\Queries\PostQuery::__construct(boolean $globalImport)

Create PostQuery instance.

If $globalImport is truthy then instance imports data from global $wp_the_query

* Visibility: **public**


#### Arguments
* $globalImport **boolean**



### getVars

    array Chayka\WP\Queries\PostQuery::getVars()

Get all the set vars in an assoc array



* Visibility: **public**




### setVars

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::setVars(array $vars)

Add vars to the set



* Visibility: **public**


#### Arguments
* $vars **array**



### setVar

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::setVar(string $key, string $value)

Set query filter var



* Visibility: **public**


#### Arguments
* $key **string**
* $value **string**



### query

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::query(boolean $globalImport)

Create PostQuery instance.

If $globalImport is truthy then instance imports data from global $wp_the_query

* Visibility: **public**
* This method is **static**.


#### Arguments
* $globalImport **boolean**



### select

    \Chayka\WP\Queries\array(PostModel) Chayka\WP\Queries\PostQuery::select()

Select all matching posts



* Visibility: **public**




### selectOne

    \Chayka\WP\Models\PostModel Chayka\WP\Queries\PostQuery::selectOne()

Select first matching post



* Visibility: **public**




### authorIdIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::authorIdIn(integer|string|array $userId)

Display posts by author, using author id



* Visibility: **public**


#### Arguments
* $userId **integer|string|array** - &lt;p&gt;(e.g 10 | &#039;10,11,12&#039; | -12 | array(10,11,12))&lt;/p&gt;



### authorNiceName

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::authorNiceName(string $userNicename)

Display posts by author, using author nicename



* Visibility: **public**


#### Arguments
* $userNicename **string**



### categoryId

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::categoryId(integer $catId)

Show posts associated with certain categories



* Visibility: **public**


#### Arguments
* $catId **integer**



### categorySlug

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::categorySlug(string $slug)

Show posts associated with certain categories



* Visibility: **public**


#### Arguments
* $slug **string**



### categoryIdsAnd

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::categoryIdsAnd(\Chayka\WP\Queries\array(int) $catIds)

Show posts associated with certain categories



* Visibility: **public**


#### Arguments
* $catIds **Chayka\WP\Queries\array(int)**



### categoryIdsIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::categoryIdsIn(\Chayka\WP\Queries\array(int) $catIds)

Show posts associated with certain categories



* Visibility: **public**


#### Arguments
* $catIds **Chayka\WP\Queries\array(int)**



### categoryIdsNotIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::categoryIdsNotIn(\Chayka\WP\Queries\array(int) $catIds)

Show posts associated with certain categories



* Visibility: **public**


#### Arguments
* $catIds **Chayka\WP\Queries\array(int)**



### tagId

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::tagId(integer $tagId)

Show posts associated with certain tags



* Visibility: **public**


#### Arguments
* $tagId **integer**



### tagSlug

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::tagSlug(string|\Chayka\WP\Queries\array(string) $slug)

Show posts associated with certain tags



* Visibility: **public**


#### Arguments
* $slug **string|Chayka\WP\Queries\array(string)**



### tagIdsAnd

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::tagIdsAnd(\Chayka\WP\Queries\array(int) $tagIds)

Show posts associated with certain tags



* Visibility: **public**


#### Arguments
* $tagIds **Chayka\WP\Queries\array(int)**



### tagIdsIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::tagIdsIn(\Chayka\WP\Queries\array(int) $tagIds)

Show posts associated with certain tags



* Visibility: **public**


#### Arguments
* $tagIds **Chayka\WP\Queries\array(int)**



### tagIdsNotIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::tagIdsNotIn(\Chayka\WP\Queries\array(int) $tagIds)

Show posts associated with certain tags



* Visibility: **public**


#### Arguments
* $tagIds **Chayka\WP\Queries\array(int)**



### tagSlugsAnd

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::tagSlugsAnd(\Chayka\WP\Queries\array(string) $tagSlugs)

Show posts associated with certain tags



* Visibility: **public**


#### Arguments
* $tagSlugs **Chayka\WP\Queries\array(string)**



### tagSlugsIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::tagSlugsIn(\Chayka\WP\Queries\array(int) $tagSlugs)

Show posts associated with certain tags



* Visibility: **public**


#### Arguments
* $tagSlugs **Chayka\WP\Queries\array(int)**



### taxonomyQuery

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::taxonomyQuery(string $taxonomy, integer|string|object|\Chayka\WP\Queries\array(id|\Chayka\WP\Queries\slug|\Chayka\WP\Queries\object) $terms, string $field, boolean $includeChildren, string $operator)

Setup taxonomy query



* Visibility: **public**


#### Arguments
* $taxonomy **string**
* $terms **integer|string|object|Chayka\WP\Queries\array(id|Chayka\WP\Queries\slug|Chayka\WP\Queries\object)**
* $field **string** - &lt;p&gt;&#039;id&#039; or &#039;slug&#039;&lt;/p&gt;
* $includeChildren **boolean**
* $operator **string**



### taxonomyQueryRelation

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::taxonomyQueryRelation(string $relation)

Set relation for multiple tax_query handling
Should come first before taxonomyQuery() call



* Visibility: **public**


#### Arguments
* $relation **string** - &lt;p&gt;&#039;AND&#039;|&#039;OR&#039;&lt;/p&gt;



### taxonomyQueryRelation_AND

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::taxonomyQueryRelation_AND()

Set taxonomy query relation to 'AND'



* Visibility: **public**




### taxonomyQueryRelation_OR

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::taxonomyQueryRelation_OR()

Set taxonomy query relation to 'OR'



* Visibility: **public**




### search

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::search(string $keyword)

Show posts based on a keyword search



* Visibility: **public**


#### Arguments
* $keyword **string**



### postId

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postId(integer $postId)

Display content based on post and page parameters.

Use post id

* Visibility: **public**


#### Arguments
* $postId **integer**



### postSlug

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postSlug(string $slug)

Display content based on post and page parameters.

Use post slug.

* Visibility: **public**


#### Arguments
* $slug **string**



### pageId

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::pageId(integer $pageId)

Display content based on post and page parameters.

Use page id.

* Visibility: **public**


#### Arguments
* $pageId **integer**



### pageSlug

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::pageSlug(string $slug)

Display content based on post and page parameters.

Display child page using the slug of the parent and the child page, separated by a slash (e.g. 'parent_slug/child_slug')

* Visibility: **public**


#### Arguments
* $slug **string**



### postParentId

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postParentId(integer $postId)

Display content based on post and page parameters.

Use page id to return only child pages. Set to 0 to return only top-level entries.

* Visibility: **public**


#### Arguments
* $postId **integer**



### postParentId_TopLevel

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postParentId_TopLevel()

Display content based on post and page parameters.

Select only top-level entries.

* Visibility: **public**




### postParentIdIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postParentIdIn(\Chayka\WP\Queries\array(int) $postIds)

Display content based on post and page parameters.

Specify posts whose parent is in an array. NOTE: Introduced in 3.6

* Visibility: **public**


#### Arguments
* $postIds **Chayka\WP\Queries\array(int)**



### postParentIdNotIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postParentIdNotIn(\Chayka\WP\Queries\array(int) $postIds)

Display content based on post and page parameters.

Specify posts whose parent is not in an array.

* Visibility: **public**


#### Arguments
* $postIds **Chayka\WP\Queries\array(int)**



### postIdIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postIdIn(\Chayka\WP\Queries\array(int) $postIds)

Display content based on post and page parameters.

Specify posts to retrieve.
ATTENTION If you use sticky posts, they will be included

* Visibility: **public**


#### Arguments
* $postIds **Chayka\WP\Queries\array(int)**



### postIdIn_StickyPosts

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postIdIn_StickyPosts()

Select sticky posts



* Visibility: **public**




### postIdNotIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postIdNotIn(\Chayka\WP\Queries\array(int) $postIds)

Display content based on post and page parameters.

Specify post NOT to retrieve.

* Visibility: **public**


#### Arguments
* $postIds **Chayka\WP\Queries\array(int)**



### postIdNotIn_StickyPosts

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postIdNotIn_StickyPosts()

Exclude sticky posts



* Visibility: **public**




### postType

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postType(string|array $postType)

Show posts associated with certain type



* Visibility: **public**


#### Arguments
* $postType **string|array**



### postType_Post

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postType_Post()

Show posts associated with type 'post'



* Visibility: **public**




### postType_Page

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postType_Page()

Show posts associated with type 'page'



* Visibility: **public**




### postType_Revision

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postType_Revision()

Show posts associated with type 'revision'



* Visibility: **public**




### postType_Attachment

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postType_Attachment()

Show posts associated with type 'attachment'



* Visibility: **public**




### postType_Any

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postType_Any()

Show posts associated with any type



* Visibility: **public**




### postStatus

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus(string|array $status)

Show posts associated with certain status



* Visibility: **public**


#### Arguments
* $status **string|array**



### postStatus_Publish

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus_Publish()

Show posts associated with status 'publish'



* Visibility: **public**




### postStatus_Pending

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus_Pending()

Show posts associated with status 'pending'



* Visibility: **public**




### postStatus_Draft

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus_Draft()

Show posts associated with status 'draft'



* Visibility: **public**




### postStatus_AutoDraft

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus_AutoDraft()

Show posts associated with status 'auto-draft'



* Visibility: **public**




### postStatus_Future

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus_Future()

Show posts associated with status 'future'



* Visibility: **public**




### postStatus_Private

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus_Private()

Show posts associated with status 'private'



* Visibility: **public**




### postStatus_Inherit

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus_Inherit()

Show posts associated with status 'inherit'



* Visibility: **public**




### postStatus_Trash

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus_Trash()

Show posts associated with status 'trash'



* Visibility: **public**




### postStatus_Any

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postStatus_Any()

Show posts associated with any status



* Visibility: **public**




### noPaging

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::noPaging(boolean $noPaging)

Show all posts or use pagination. Default value is 'false', use paging



* Visibility: **public**


#### Arguments
* $noPaging **boolean**



### postsPerPage

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postsPerPage(integer $perPage)

Set number of post to show per page
(available with Version 2.1, replaced showposts parameter).

Use 'posts_per_page'=>-1 to show all posts.
Set the 'paged' parameter if pagination is off after using this parameter.
Note: if the query is in a feed, wordpress overwrites this parameter with the stored 'posts_per_rss' option.
To reimpose the limit, try using the 'post_limits' filter, or filter 'pre_option_posts_per_rss' and return -1

* Visibility: **public**


#### Arguments
* $perPage **integer**



### postsPerPage_All

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postsPerPage_All()

Select all entries, no pagination considered



* Visibility: **public**




### postsPerArchivePage

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::postsPerArchivePage(integer $perPage)

Set number of posts to show per page - on archive pages only.

Over-rides posts_per_page and showposts on pages where is_archive() or is_search() would be true

* Visibility: **public**


#### Arguments
* $perPage **integer**



### pageNumber

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::pageNumber(integer $page)

Number of page.

Show the posts that would normally show up just on page X when using the "Older Entries" link

* Visibility: **public**


#### Arguments
* $page **integer**



### offset

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::offset(integer $offset)

Set number of post to displace or pass over.

Warning: Setting the offset parameter overrides/ignores the paged parameter
and breaks pagination

* Visibility: **public**


#### Arguments
* $offset **integer**



### ignoreStickyPosts

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::ignoreStickyPosts(boolean $ignore)

ignore sticky posts or not
(available with Version 3.1, replaced caller_get_posts parameter).

Default value is 0 - don't ignore sticky posts.
Note: ignore/exclude sticky posts being included at the beginning of posts returned,
but the sticky post will still be returned in the natural order of that list of posts returned

* Visibility: **public**


#### Arguments
* $ignore **boolean**



### order

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::order(string $order)

Designates the ascending or descending order of the 'orderby' parameter.

Defaults to 'DESC'

* Visibility: **public**


#### Arguments
* $order **string**



### order_ASC

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::order_ASC()

Sort entries in ascending order



* Visibility: **public**




### order_DESC

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::order_DESC()

Sort entries in descending order



* Visibility: **public**




### orderBy

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy(string $orderBy)

Sort retrieved posts by parameter. Defaults to 'date'.



* Visibility: **public**


#### Arguments
* $orderBy **string**



### orderBy_None

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_None()

Do not sort entries. Entries will be returned in the order of creation.



* Visibility: **public**




### orderBy_ID

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_ID()

Sort entries by id



* Visibility: **public**




### orderBy_AuthorId

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_AuthorId()

Sort entries by author user id



* Visibility: **public**




### orderBy_Title

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_Title()

Sort entries by title



* Visibility: **public**




### orderBy_Slug

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_Slug()

Sort entries by slug



* Visibility: **public**




### orderBy_Date

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_Date()

Sort entries by creation date



* Visibility: **public**




### orderBy_Modified

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_Modified()

Sort entries by last modification date



* Visibility: **public**




### orderBy_ParentId

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_ParentId()

Sort entries by parent post id



* Visibility: **public**




### orderBy_Rand

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_Rand()

Return entries in random order



* Visibility: **public**




### orderBy_CommentCount

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_CommentCount()

Sort entries by comment count



* Visibility: **public**




### orderBy_MenuOrder

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_MenuOrder()

Order by Page Order. Used most often for Pages (Order field in the Edit Page Attributes box)
and for Attachments (the integer fields in the Insert / Upload Media Gallery dialog),
but could be used for any post type with distinct 'menu_order' values (they all default to 0).



* Visibility: **public**




### orderBy_MetaValue

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_MetaValue(string $metaKey)

Note that a 'meta_key=keyname' must also be present in the query.

Note also that the sorting will be alphabetical which is fine for strings
(i.e. words), but can be unexpected for numbers
(e.g. 1, 3, 34, 4, 56, 6, etc, rather than 1, 3, 4, 6, 34, 56 as you might naturally expect).
Use 'meta_value_num' instead for numeric values.

* Visibility: **public**


#### Arguments
* $metaKey **string**



### orderBy_MetaValueNum

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_MetaValueNum(string $metaKey)

Order by numeric meta value (available with Version 2.8).

Also note that a 'meta_key=keyname' must also be present in the query.
This value allows for numerical sorting as noted above in 'meta_value'.

* Visibility: **public**


#### Arguments
* $metaKey **string**



### orderBy_PostIn

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::orderBy_PostIn(string|array $postIds)

Preserve post ID order given in the post__in array (available with Version 3.5).



* Visibility: **public**


#### Arguments
* $postIds **string|array**



### year

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::year(integer $year)

Show posts associated with a certain time period.



* Visibility: **public**


#### Arguments
* $year **integer** - &lt;p&gt;4 digit year (e.g. 2011)&lt;/p&gt;



### month

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::month(integer $month)

Show posts associated with a certain time period.



* Visibility: **public**


#### Arguments
* $month **integer** - &lt;p&gt;Month number (from 1 to 12)&lt;/p&gt;



### yearMonth

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::yearMonth(integer $yearMonth)

Show posts associated with a certain time period.



* Visibility: **public**


#### Arguments
* $yearMonth **integer** - &lt;p&gt;YearMonth (For e.g.: 201307)&lt;/p&gt;



### week

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::week(integer $week)

Show posts associated with a certain time period.

Uses the MySQL WEEK command. The mode is dependent on the "start_of_week" option

* Visibility: **public**


#### Arguments
* $week **integer** - &lt;p&gt;Week of the year (from 0 to 53).&lt;/p&gt;



### day

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::day(integer $day)

Show posts associated with a certain time period.



* Visibility: **public**


#### Arguments
* $day **integer** - &lt;p&gt;Day of the month (from 1 to 31).&lt;/p&gt;



### hour

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::hour(integer $hour)

Show posts associated with a certain time period.



* Visibility: **public**


#### Arguments
* $hour **integer** - &lt;p&gt;Hour (from 0 to 23).&lt;/p&gt;



### minute

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::minute(integer $minute)

Show posts associated with a certain time period.



* Visibility: **public**


#### Arguments
* $minute **integer** - &lt;p&gt;Minute (from 0 to 60).&lt;/p&gt;



### second

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::second(integer $second)

Show posts associated with a certain time period.



* Visibility: **public**


#### Arguments
* $second **integer** - &lt;p&gt;Second (0 to 60).&lt;/p&gt;



### metaKey

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::metaKey(string $key)

Custom field key.



* Visibility: **public**


#### Arguments
* $key **string**



### metaValue

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::metaValue(string $value)

Custom field value



* Visibility: **public**


#### Arguments
* $value **string**



### metaValueNum

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::metaValueNum(\Chayka\WP\Queries\number $value)

Custom field numeric value



* Visibility: **public**


#### Arguments
* $value **Chayka\WP\Queries\number**



### metaCompare

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::metaCompare(string $compare)

Operator to test the 'meta_value'.

Possible values are '!=', '>', '>=', '<', or '<='. Default value is '='.

* Visibility: **public**


#### Arguments
* $compare **string**



### metaQuery

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::metaQuery(string $key, string|array $value, string $compare, string $type)

Custom field parameters (available with Version 3.1)



* Visibility: **public**


#### Arguments
* $key **string** - &lt;p&gt;Custom field key&lt;/p&gt;
* $value **string|array** - &lt;p&gt;ustom field value
(Note: Array support is limited to a compare value of
&#039;IN&#039;, &#039;NOT IN&#039;, &#039;BETWEEN&#039;, or &#039;NOT BETWEEN&#039;).
Note: Due to bug #23268, value is required for EXISTS and NOT EXISTS
comparisons to work correctly. You may use an empty string for the value
as a workaround. If empty quotes (&quot;&quot;) doesn&#039;t work, pass in NULL for the value.&lt;/p&gt;
* $compare **string** - &lt;p&gt;Operator to test. Possible values are
&#039;=&#039;, &#039;!=&#039;, &#039;&gt;&#039;, &#039;&gt;=&#039;, &#039;&lt;&#039;, &#039;&lt;=&#039;, &#039;LIKE&#039;, &#039;NOT LIKE&#039;, &#039;IN&#039;, &#039;NOT IN&#039;, &#039;BETWEEN&#039;, &#039;NOT BETWEEN&#039;,
&#039;EXISTS&#039; (only in WP &gt;= 3.5), and &#039;NOT EXISTS&#039; (also only in WP &gt;= 3.5).
Default value is &#039;=&#039;.&lt;/p&gt;
* $type **string** - &lt;p&gt;Custom field type. Possible values are
&#039;NUMERIC&#039;, &#039;BINARY&#039;, &#039;CHAR&#039;, &#039;DATE&#039;, &#039;DATETIME&#039;, &#039;DECIMAL&#039;, &#039;SIGNED&#039;, &#039;TIME&#039;, &#039;UNSIGNED&#039;.
Default value is &#039;CHAR&#039;.&lt;/p&gt;



### metaQueryRelation

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::metaQueryRelation(string $relation)

Set relation for multiple meta_query handling.

Should come first before metaQuery() call.

* Visibility: **public**


#### Arguments
* $relation **string**



### metaQueryRelation_AND

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::metaQueryRelation_AND()

Set 'AND' relation for multiple meta_query handling.

Should come first before metaQuery() call.

* Visibility: **public**




### metaQueryRelation_OR

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::metaQueryRelation_OR()

Set 'OR' relation for multiple meta_query handling.

Should come first before metaQuery() call.

* Visibility: **public**




### userHasCapability

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::userHasCapability(string $permCapability)

Display posts, if the user has the appropriate capability



* Visibility: **public**


#### Arguments
* $permCapability **string**



### cachePosts

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::cachePosts(boolean $cache)

Stop the data retrieved from being added to the cache.



* Visibility: **public**


#### Arguments
* $cache **boolean**



### cacheMeta

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::cacheMeta(boolean $cache)

Stop the data retrieved from being added to the cache.



* Visibility: **public**


#### Arguments
* $cache **boolean**



### cacheTerms

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::cacheTerms(boolean $cache)

Stop the data retrieved from being added to the cache.



* Visibility: **public**


#### Arguments
* $cache **boolean**



### fields

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::fields(string|\Chayka\WP\Queries\array(string) $fields)

Set return values.



* Visibility: **public**


#### Arguments
* $fields **string|Chayka\WP\Queries\array(string)**



### fields_All

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::fields_All()

Setup to return all fields



* Visibility: **public**




### fields_Ids

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::fields_Ids()

Setup to return all ids



* Visibility: **public**




### fields_ID_ParentId

    \Chayka\WP\Queries\PostQuery Chayka\WP\Queries\PostQuery::fields_ID_ParentId()

Setup to return array of id=>parent relations



* Visibility: **public**




### setupDateQuery

    \Chayka\WP\Queries\PostDateQuery Chayka\WP\Queries\PostQuery::setupDateQuery()

Get date query model available for setup



* Visibility: **public**




### dateQuery

    mixed Chayka\WP\Queries\PostQuery::dateQuery($dateQuery)

Set PostDateQuery



* Visibility: **public**


#### Arguments
* $dateQuery **mixed**


