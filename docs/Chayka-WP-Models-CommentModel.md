Chayka\WP\Models\CommentModel
===============

CommentModel is a wrapper for WP comments.

It has all the setters and getters, CRUD methods.
All in one place, amazing!


* Class name: CommentModel
* Namespace: Chayka\WP\Models
* This class implements: [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md), Chayka\Helpers\JsonReady, Chayka\Helpers\InputReady, [Chayka\WP\Helpers\AclReady](Chayka-WP-Helpers-AclReady.md)




Properties
----------


### $id

    protected integer $id

Comment unique id



* Visibility: **protected**


### $postId

    protected integer $postId

Comment post id



* Visibility: **protected**


### $parentId

    protected integer $parentId

Parent comment id



* Visibility: **protected**


### $userId

    protected integer $userId

Comment author user id



* Visibility: **protected**


### $author

    protected string $author

Comment author name



* Visibility: **protected**


### $email

    protected string $email

Comment author email



* Visibility: **protected**


### $url

    protected string $url

Comment author website address



* Visibility: **protected**


### $ip

    protected string $ip

Comment author IP address



* Visibility: **protected**


### $dtCreated

    protected \DateTime $dtCreated

Timestamp when comment was created



* Visibility: **protected**


### $content

    protected string $content

Comment content



* Visibility: **protected**


### $karma

    protected integer $karma

Comment karma - some score that can be modified using vote()



* Visibility: **protected**


### $karmaDelta

    protected integer $karmaDelta

Karma delta is actually current user vote, can be -1, 0, 1.

In current implementation is stored in the session,
override if you need more sophisticated logic.

* Visibility: **protected**


### $isApproved

    protected integer $isApproved

Comment approval status: 0, 1, 'spam'



* Visibility: **protected**


### $agent

    protected string $agent

User agent (browser) the comment is made from



* Visibility: **protected**


### $type

    protected string $type

Comment type



* Visibility: **protected**


### $wpComment

    protected \stdClass $wpComment

Original WP comment object



* Visibility: **protected**


### $validationErrors

    protected array $validationErrors = array()

Hash map of validation errors. Part of InputReady interface implementation.



* Visibility: **protected**
* This property is **static**.


### $commentsCacheById

    protected array $commentsCacheById = array()

Cache by comment id, second selectById() fetches comment from here.



* Visibility: **protected**
* This property is **static**.


### $commentsCacheByPostId

    protected array $commentsCacheByPostId = array()

Cache by comment post id, second selectByPostId() fetches comments from here.



* Visibility: **protected**
* This property is **static**.


### $jsonMetaFields

    protected array $jsonMetaFields = array()

Stores set of meta fields that should be included in JSON



* Visibility: **protected**
* This property is **static**.


Methods
-------


### __construct

    mixed Chayka\WP\Models\CommentModel::__construct()

Comment model constructor, initializes all fields with default values,
'author' fields are initialized using current user data.



* Visibility: **public**




### init

    mixed Chayka\WP\Models\CommentModel::init()

Initialize comment with all the known data (user, ip, etc.)



* Visibility: **public**




### getId

    mixed Chayka\WP\Helpers\DbReady::getId()

Get instance id



* Visibility: **public**
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)




### setId

    \Chayka\WP\Helpers\DbReady Chayka\WP\Helpers\DbReady::setId($id)

Set instance id



* Visibility: **public**
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)


#### Arguments
* $id **mixed**



### getPostId

    integer Chayka\WP\Models\CommentModel::getPostId()

Get comment post id



* Visibility: **public**




### setPostId

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setPostId(integer $postId)

Set post comment id



* Visibility: **public**


#### Arguments
* $postId **integer**



### getUserId

    integer Chayka\WP\Models\CommentModel::getUserId()

Get post author user id



* Visibility: **public**




### setUserId

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setUserId(integer $userId)

Set post author user id



* Visibility: **public**


#### Arguments
* $userId **integer**



### getUser

    \Chayka\WP\Models\UserModel Chayka\WP\Models\CommentModel::getUser()

Get author UserModel object



* Visibility: **public**




### getAuthor

    string Chayka\WP\Models\CommentModel::getAuthor()

Get author name.

If userId is set and the corresponding UserModel exists method returns
display name (if set) or user login.
If userId not set or UserModel was deleted method returns comment_author field.

* Visibility: **public**




### setAuthor

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setAuthor(string $author)

Set author name



* Visibility: **public**


#### Arguments
* $author **string**



### getEmail

    string Chayka\WP\Models\CommentModel::getEmail()

Get author email.

If userId is set and the corresponding UserModel exists method returns
user email.
If userId not set or UserModel was deleted method returns comment_author_email field.

* Visibility: **public**




### setEmail

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setEmail(string $email)

Set author email.



* Visibility: **public**


#### Arguments
* $email **string**



### getUrl

    string Chayka\WP\Models\CommentModel::getUrl()

Get author url.

If userId is set and the corresponding UserModel exists method returns
user url.
If userId not set or UserModel was deleted method returns comment_author_url field.

* Visibility: **public**




### setUrl

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setUrl(string $url)

Set author url



* Visibility: **public**


#### Arguments
* $url **string**



### getIp

    string Chayka\WP\Models\CommentModel::getIp()

Get author ip



* Visibility: **public**




### setIp

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setIp(string $ip)

Set comment ip



* Visibility: **public**


#### Arguments
* $ip **string**



### getDtCreated

    \DateTime Chayka\WP\Models\CommentModel::getDtCreated()

Get comment creation datetime



* Visibility: **public**




### setDtCreated

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setDtCreated(\DateTime $dtCreated)

Set comment creation datetime



* Visibility: **public**


#### Arguments
* $dtCreated **DateTime**



### getDtCreatedGMT

    \DateTime Chayka\WP\Models\CommentModel::getDtCreatedGMT()

Get post creation time (GMT)



* Visibility: **public**




### getContent

    string Chayka\WP\Models\CommentModel::getContent()

Get comment content



* Visibility: **public**




### setContent

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setContent(string $content)

Set plain text comment content



* Visibility: **public**


#### Arguments
* $content **string**



### getKarma

    integer Chayka\WP\Models\CommentModel::getKarma()

Get comment karma



* Visibility: **public**




### setKarma

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setKarma(integer $karma)

Set karma. Used within model only.

If you need to adjust karma use voteUp() and voteDown() or vote() instead.

* Visibility: **public**


#### Arguments
* $karma **integer**



### getKarmaDelta

    integer Chayka\WP\Models\CommentModel::getKarmaDelta()

Get current user karma delta (upvote or downvote) for today



* Visibility: **public**




### setKarmaDelta

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setKarmaDelta(integer $delta)

Set comment karma delta.

Used within model only.
Use voteUp() and voteDown() or vote() instead

* Visibility: **public**


#### Arguments
* $delta **integer**



### vote

    integer Chayka\WP\Models\CommentModel::vote($delta)

Vote for comment.



* Visibility: **public**


#### Arguments
* $delta **mixed**



### voteUp

    integer Chayka\WP\Models\CommentModel::voteUp()

Vote up for comment



* Visibility: **public**




### voteDown

    integer Chayka\WP\Models\CommentModel::voteDown()

Vote down



* Visibility: **public**




### getIsApproved

    integer|string Chayka\WP\Models\CommentModel::getIsApproved()

Is comment approved



* Visibility: **public**




### setIsApproved

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setIsApproved(integer|string $isApproved)

Set approved flag



* Visibility: **public**


#### Arguments
* $isApproved **integer|string** - &lt;p&gt;0|1|&#039;spam&#039;&lt;/p&gt;



### getAgent

    string Chayka\WP\Models\CommentModel::getAgent()

Get comment user-agent (browser signature)



* Visibility: **public**




### setAgent

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setAgent(string $agent)

Set comment user-agent (browser signature)



* Visibility: **public**


#### Arguments
* $agent **string**



### getType

    string Chayka\WP\Models\CommentModel::getType()

Get comment type (empty by default)



* Visibility: **public**




### setType

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setType(string $type)

Set comment type



* Visibility: **public**


#### Arguments
* $type **string**



### getParentId

    integer Chayka\WP\Models\CommentModel::getParentId()

Get parent comment id



* Visibility: **public**




### setParentId

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setParentId(integer $parentId)

Set parent comment id



* Visibility: **public**


#### Arguments
* $parentId **integer**



### getWpComment

    object Chayka\WP\Models\CommentModel::getWpComment()

Get original WP_Comment (if preserved)



* Visibility: **public**




### setWpComment

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::setWpComment(object $wpComment)

Preserve original WP_Comment



* Visibility: **public**


#### Arguments
* $wpComment **object**



### __get

    mixed Chayka\WP\Models\CommentModel::__get($name)

Magic getter that allows to use CommentModel where wpComment should be used



* Visibility: **public**


#### Arguments
* $name **mixed**



### getDbIdColumn

    mixed Chayka\WP\Helpers\DbReady::getDbIdColumn()

Get id column name in db table



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)




### getDbTable

    string Chayka\WP\Helpers\DbReady::getDbTable()

Get db table name for the instance storage.



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)




### getValidationErrors

    \Chayka\WP\Models\array[field]='Error Chayka\WP\Models\CommentModel::getValidationErrors()

Get validation errors after unpacking from request input
Should be set by validateInput



* Visibility: **public**
* This method is **static**.




### addValidationErrors

    array|mixed Chayka\WP\Models\CommentModel::addValidationErrors($errors)

Add validation errors after unpacking from request input



* Visibility: **public**
* This method is **static**.


#### Arguments
* $errors **mixed**



### unpackJsonItem

    \Chayka\Helpers\JsonReady|void Chayka\WP\Models\CommentModel::unpackJsonItem(array $input)

Unpacks request input.

Used by REST Controllers.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $input **array**



### validateInput

    boolean Chayka\WP\Models\CommentModel::validateInput(array $input, \Chayka\WP\Models\CommentModel $oldState)

Validates input and sets $validationErrors



* Visibility: **public**
* This method is **static**.


#### Arguments
* $input **array**
* $oldState **[Chayka\WP\Models\CommentModel](Chayka-WP-Models-CommentModel.md)**



### unpackDbRecord

    \Chayka\WP\Helpers\DbReady Chayka\WP\Helpers\DbReady::unpackDbRecord(array|object $dbRecord)

Unpacks db result object into this instance



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)


#### Arguments
* $dbRecord **array|object**



### packDbRecord

    array Chayka\WP\Helpers\DbReady::packDbRecord(boolean $forUpdate)

Packs this instance for db insert/update



* Visibility: **public**
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)


#### Arguments
* $forUpdate **boolean**



### insert

    integer Chayka\WP\Helpers\DbReady::insert()

Insert current instance to db and return object id



* Visibility: **public**
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)




### update

    integer Chayka\WP\Helpers\DbReady::update()

Update corresponding db row in db and return object id.



* Visibility: **public**
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)




### delete

    boolean Chayka\WP\Helpers\DbReady::delete()

Delete corresponding db row from db.



* Visibility: **public**
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)




### deleteById

    boolean Chayka\WP\Models\CommentModel::deleteById(integer $commentId, boolean $forceDelete)

Deletes comment with the specified $commentId from db table



* Visibility: **public**
* This method is **static**.


#### Arguments
* $commentId **integer**
* $forceDelete **boolean**



### selectById

    mixed Chayka\WP\Helpers\DbReady::selectById($id, boolean $useCache)

Select instance from db by id.



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)


#### Arguments
* $id **mixed**
* $useCache **boolean**



### selectComments

    \Chayka\WP\Models\array(CommentModel) Chayka\WP\Models\CommentModel::selectComments(array $wpCommentsQueryArgs)

Select models using WP_Comment_Query syntax.

The total count of comments of the specified post is stored in post model.
(could be inconsistent though)

* Visibility: **public**
* This method is **static**.


#### Arguments
* $wpCommentsQueryArgs **array**



### query

    \Chayka\WP\Queries\CommentQuery Chayka\WP\Models\CommentModel::query(integer $postId)

Get CommentQuery object to create a query.

Call ->select() to fetch queried models;
The total count of comments of the specified post is stored in post model.
(could be inconsistent though)

* Visibility: **public**
* This method is **static**.


#### Arguments
* $postId **integer**



### getCommentMeta

    mixed Chayka\WP\Models\CommentModel::getCommentMeta(integer $commentId, string $key, boolean $single)

Get comment meta single key-value pair or all key-values



* Visibility: **public**
* This method is **static**.


#### Arguments
* $commentId **integer** - &lt;p&gt;Comment ID.&lt;/p&gt;
* $key **string** - &lt;p&gt;Optional. The meta key to retrieve. By default, returns data for all keys.&lt;/p&gt;
* $single **boolean** - &lt;p&gt;Whether to return a single value.&lt;/p&gt;



### updateCommentMeta

    boolean Chayka\WP\Models\CommentModel::updateCommentMeta(integer $commentId, string $key, string $value, string $oldValue)

Update comment meta value for the specified key in the DB



* Visibility: **public**
* This method is **static**.


#### Arguments
* $commentId **integer**
* $key **string**
* $value **string**
* $oldValue **string**



### getMeta

    mixed Chayka\WP\Models\CommentModel::getMeta(string $key, boolean $single)

Get comment meta single key-value pair or all key-values



* Visibility: **public**


#### Arguments
* $key **string** - &lt;p&gt;Optional. The meta key to retrieve. By default, returns data for all keys.&lt;/p&gt;
* $single **boolean** - &lt;p&gt;Whether to return a single value.&lt;/p&gt;



### updateMeta

    boolean Chayka\WP\Models\CommentModel::updateMeta(string $key, string $value, string $oldValue)

Update comment meta value for the specified key in the DB



* Visibility: **public**


#### Arguments
* $key **string**
* $value **string**
* $oldValue **string**



### populateWpGlobals

    array|boolean|null|object Chayka\WP\Models\CommentModel::populateWpGlobals()

Populates this comment for old school WP use.

Defines global variable $comment.

* Visibility: **public**




### setJsonMetaFields

    mixed Chayka\WP\Models\CommentModel::setJsonMetaFields(\Chayka\WP\Models\array(string) $metaFields)

Set meta fields that should be populated to json



* Visibility: **public**
* This method is **static**.


#### Arguments
* $metaFields **Chayka\WP\Models\array(string)**



### addJsonMetaField

    mixed Chayka\WP\Models\CommentModel::addJsonMetaField(string $fieldName)

Add meta field name that should be populated to json



* Visibility: **public**
* This method is **static**.


#### Arguments
* $fieldName **string**



### removeJsonMetaField

    mixed Chayka\WP\Models\CommentModel::removeJsonMetaField(string $fieldName)

Remove meta field name that should not be populated to json



* Visibility: **public**
* This method is **static**.


#### Arguments
* $fieldName **string**



### packJsonItem

    array Chayka\WP\Models\CommentModel::packJsonItem()

Packs this post into assoc array for JSON representation.

Used for API Output

* Visibility: **public**




### flushCache

    mixed Chayka\WP\Models\CommentModel::flushCache()

Flushes cache used for selectById()



* Visibility: **public**
* This method is **static**.




### getCommentsCacheById

    \Chayka\WP\Models\CommentModel Chayka\WP\Models\CommentModel::getCommentsCacheById(integer $id)

Get comment by $id from cache.

It gets to cache once it was unpacked by unpackDbRecord()

* Visibility: **public**
* This method is **static**.


#### Arguments
* $id **integer**



### getCommentsCacheByPostId

    \Chayka\WP\Models\array(CommentModel) Chayka\WP\Models\CommentModel::getCommentsCacheByPostId(integer $postId)

Get comments by $postId from cache.

Comments gets to cache once it was unpacked by unpackDbRecord()

* Visibility: **public**
* This method is **static**.


#### Arguments
* $postId **integer**



### userCan

    mixed Chayka\WP\Helpers\AclReady::userCan(string $privilege, \Chayka\WP\Models\UserModel|null $user)

Check if user has $privilege over the model instance



* Visibility: **public**
* This method is defined by [Chayka\WP\Helpers\AclReady](Chayka-WP-Helpers-AclReady.md)


#### Arguments
* $privilege **string**
* $user **[Chayka\WP\Models\UserModel](Chayka-WP-Models-UserModel.md)|null**


