Chayka\WP\Models\UserModel
===============

Class implemented to handle user actions and manipulations
Used for authentication, registration, update, delete and avatars management




* Class name: UserModel
* Namespace: Chayka\WP\Models
* This class implements: [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md), Chayka\Helpers\JsonReady, Chayka\Helpers\InputReady, [Chayka\WP\Helpers\AclReady](Chayka-WP-Helpers-AclReady.md)




Properties
----------


### $userCacheById

    protected array $userCacheById = array()

User models cached by user ids



* Visibility: **protected**
* This property is **static**.


### $userCacheByEmail

    protected array $userCacheByEmail = array()

User models cached by emails



* Visibility: **protected**
* This property is **static**.


### $userCacheByLogin

    protected array $userCacheByLogin = array()

User models cached by logins



* Visibility: **protected**
* This property is **static**.


### $jsonMetaFields

    protected array $jsonMetaFields = array()

An array that contains set of meta fields
that should be published when model outputted as json



* Visibility: **protected**
* This property is **static**.


### $currentUser

    protected \Chayka\WP\Models\UserModel $currentUser

UserModel instance of current user



* Visibility: **protected**
* This property is **static**.


### $validationErrors

    protected array $validationErrors

Array of validation errors, part of InputReady interface implementation



* Visibility: **protected**
* This property is **static**.


### $id

    protected integer $id

User id



* Visibility: **protected**


### $login

    protected string $login

User login



* Visibility: **protected**


### $password

    protected string $password

User password



* Visibility: **protected**


### $nicename

    protected string $nicename

User nice name - URL slug



* Visibility: **protected**


### $url

    protected string $url

User website url



* Visibility: **protected**


### $displayName

    protected string $displayName

User display name



* Visibility: **protected**


### $firstName

    protected string $firstName

User first name



* Visibility: **protected**


### $lastName

    protected string $lastName

User last name



* Visibility: **protected**


### $description

    protected string $description

User description



* Visibility: **protected**


### $richEditing

    protected boolean $richEditing

Flag that allows user to perform rich editing



* Visibility: **protected**


### $role

    protected string $role

The highest of user roles



* Visibility: **protected**


### $capabilities

    protected array $capabilities

Set of user capabilities



* Visibility: **protected**


### $email

    protected string $email

User email



* Visibility: **protected**


### $registered

    protected \DateTime $registered

The date time when user account was registered



* Visibility: **protected**


### $jabber

    protected string $jabber

Jabber account



* Visibility: **protected**


### $aim

    protected string $aim

AIM account



* Visibility: **protected**


### $yim

    protected string $yim

YIM account



* Visibility: **protected**


### $wpUser

    protected \WP_User $wpUser

WP user object that is being wrapper by this model



* Visibility: **protected**


Methods
-------


### __construct

    mixed Chayka\WP\Models\UserModel::__construct()

UserModel constructor



* Visibility: **public**




### init

    mixed Chayka\WP\Models\UserModel::init()

Initialization with guest data



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



### getLogin

    string Chayka\WP\Models\UserModel::getLogin()

Get user login



* Visibility: **public**




### setLogin

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setLogin($login)

Set user login



* Visibility: **public**


#### Arguments
* $login **mixed**



### getPassword

    string Chayka\WP\Models\UserModel::getPassword()

Get user password



* Visibility: **public**




### setPassword

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setPassword(string $password)

Set password



* Visibility: **public**


#### Arguments
* $password **string**



### getNicename

    string Chayka\WP\Models\UserModel::getNicename()

Get user nicename (slug)



* Visibility: **public**




### setNicename

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setNicename($nicename)

Set user nicename (slug)



* Visibility: **public**


#### Arguments
* $nicename **mixed**



### getUrl

    string Chayka\WP\Models\UserModel::getUrl()

Get user's site url.

Not the user's profile link.

* Visibility: **public**




### setUrl

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setUrl(string $url)

Set user's site url.

Not the user's profile link.

* Visibility: **public**


#### Arguments
* $url **string**



### getDisplayName

    string Chayka\WP\Models\UserModel::getDisplayName()

Get user display name



* Visibility: **public**




### setDisplayName

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setDisplayName(string $displayName)

Set user display name



* Visibility: **public**


#### Arguments
* $displayName **string**



### getFirstName

    string Chayka\WP\Models\UserModel::getFirstName()

Get first name



* Visibility: **public**




### setFirstName

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setFirstName(string $firstName)

Set first name



* Visibility: **public**


#### Arguments
* $firstName **string**



### getLastName

    string Chayka\WP\Models\UserModel::getLastName()

Get last name



* Visibility: **public**




### setLastName

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setLastName($lastName)

Set last name



* Visibility: **public**


#### Arguments
* $lastName **mixed**



### getDescription

    string Chayka\WP\Models\UserModel::getDescription()

Get user description



* Visibility: **public**




### setDescription

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setDescription(string $description)

Set user description



* Visibility: **public**


#### Arguments
* $description **string**



### getRichEditing

    mixed Chayka\WP\Models\UserModel::getRichEditing()

Get 'rich_editing'



* Visibility: **public**




### setRichEditing

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setRichEditing($richEditing)

Set 'rich_editing'



* Visibility: **public**


#### Arguments
* $richEditing **mixed**



### getCapabilities

    array Chayka\WP\Models\UserModel::getCapabilities()

Get set of user capabilities



* Visibility: **public**




### hasRole

    boolean Chayka\WP\Models\UserModel::hasRole(string $role)

Check if user has role



* Visibility: **public**


#### Arguments
* $role **string**



### hasCapability

    boolean Chayka\WP\Models\UserModel::hasCapability($capability)

Check if user has capability



* Visibility: **public**


#### Arguments
* $capability **mixed**



### getRole

    string Chayka\WP\Models\UserModel::getRole()

Get the best (most powerful) available role



* Visibility: **public**




### setRole

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setRole($role)

Set user role
TODO: check if it saves somehow



* Visibility: **public**


#### Arguments
* $role **mixed**



### getEmail

    string Chayka\WP\Models\UserModel::getEmail()

Get user email



* Visibility: **public**




### setEmail

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setEmail(string $email)

Set user model



* Visibility: **public**


#### Arguments
* $email **string**



### getRegistered

    \DateTime Chayka\WP\Models\UserModel::getRegistered()

Get user registration datetime



* Visibility: **public**




### setRegistered

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setRegistered(\DateTime $registered)

Set user registration datetime



* Visibility: **public**


#### Arguments
* $registered **DateTime**



### getJabber

    string Chayka\WP\Models\UserModel::getJabber()

Set jabebr



* Visibility: **public**




### setJabber

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setJabber(string $jabber)

Get jabber



* Visibility: **public**


#### Arguments
* $jabber **string**



### getAim

    string Chayka\WP\Models\UserModel::getAim()

Get aim



* Visibility: **public**




### setAim

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setAim(string $aim)

Set aim



* Visibility: **public**


#### Arguments
* $aim **string**



### getYim

    string Chayka\WP\Models\UserModel::getYim()

Get yim



* Visibility: **public**




### setYim

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setYim(string $yim)

Set yim



* Visibility: **public**


#### Arguments
* $yim **string**



### getProfileLink

    string Chayka\WP\Models\UserModel::getProfileLink()

Get user profile link



* Visibility: **public**




### getWpUser

    object|\WP_User Chayka\WP\Models\UserModel::getWpUser()

Get original WP_User model if one is stored



* Visibility: **public**




### setWpUser

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::setWpUser(object|\WP_User $wpUser)

Set original WP_User model



* Visibility: **public**


#### Arguments
* $wpUser **object|WP_User**



### __get

    mixed Chayka\WP\Models\UserModel::__get($name)

Magic getter that allows to use UserModel where wpUser should be used



* Visibility: **public**


#### Arguments
* $name **mixed**



### getUserMeta

    mixed Chayka\WP\Models\UserModel::getUserMeta(integer $userId, string $key, boolean $single)

Get user meta single key-value pair or all key-values



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userId **integer** - &lt;p&gt;Comment ID.&lt;/p&gt;
* $key **string** - &lt;p&gt;Optional. The meta key to retrieve. By default, returns data for all keys.&lt;/p&gt;
* $single **boolean** - &lt;p&gt;Whether to return a single value.&lt;/p&gt;



### updateUserMeta

    boolean Chayka\WP\Models\UserModel::updateUserMeta(integer $userId, string $key, string $value, string $oldValue)

Update user meta value for the specified key in the DB



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userId **integer**
* $key **string**
* $value **string**
* $oldValue **string**



### deleteUserMeta

    boolean Chayka\WP\Models\UserModel::deleteUserMeta(integer $userId, string $key, string $oldValue)

Remove metadata matching criteria from a user.

You can match based on the key, or key and value. Removing based on key and
value, will keep from removing duplicate metadata with the same key. It also
allows removing all metadata matching key, if needed.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $userId **integer**
* $key **string**
* $oldValue **string**



### getMeta

    mixed Chayka\WP\Models\UserModel::getMeta(string $key, boolean $single)

Get user meta single key-value pair or all key-values



* Visibility: **public**


#### Arguments
* $key **string** - &lt;p&gt;Optional. The meta key to retrieve. By default, returns data for all keys.&lt;/p&gt;
* $single **boolean** - &lt;p&gt;Whether to return a single value.&lt;/p&gt;



### updateMeta

    boolean Chayka\WP\Models\UserModel::updateMeta(string $key, string $value, string $oldValue)

Update user meta value for the specified key in the DB



* Visibility: **public**


#### Arguments
* $key **string**
* $value **string**
* $oldValue **string**



### deleteMeta

    mixed Chayka\WP\Models\UserModel::deleteMeta($key, string $oldValue)

Remove metadata matching criteria from a user.

You can match based on the key, or key and value. Removing based on key and
value, will keep from removing duplicate metadata with the same key. It also
allows removing all metadata matching key, if needed.

* Visibility: **public**


#### Arguments
* $key **mixed**
* $oldValue **string**



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

    boolean Chayka\WP\Models\UserModel::deleteById(integer $userId, integer $reassignUserId)

Deletes user with the specified $userId from db table



* Visibility: **public**
* This method is **static**.


#### Arguments
* $userId **integer**
* $reassignUserId **integer**



### selectById

    mixed Chayka\WP\Helpers\DbReady::selectById($id, boolean $useCache)

Select instance from db by id.



* Visibility: **public**
* This method is **static**.
* This method is defined by [Chayka\WP\Helpers\DbReady](Chayka-WP-Helpers-DbReady.md)


#### Arguments
* $id **mixed**
* $useCache **boolean**



### selectByLogin

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::selectByLogin(string $login, boolean $useCache)

Select user by login



* Visibility: **public**
* This method is **static**.


#### Arguments
* $login **string**
* $useCache **boolean**



### selectByEmail

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::selectByEmail($email, boolean $useCache)

Select user by email



* Visibility: **public**
* This method is **static**.


#### Arguments
* $email **mixed**
* $useCache **boolean**



### selectBySlug

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::selectBySlug($slug)

Select user by slug (nicename)



* Visibility: **public**
* This method is **static**.


#### Arguments
* $slug **mixed**



### selectUsers

    array Chayka\WP\Models\UserModel::selectUsers($wpUserQueryArgs)

Select users by filtering args.

Use UserModel::query() helper instead.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $wpUserQueryArgs **mixed**



### query

    \Chayka\WP\Queries\UserQuery Chayka\WP\Models\UserModel::query()

Get query helper instance.



* Visibility: **public**
* This method is **static**.




### currentUser

    \Chayka\WP\Models\UserModel Chayka\WP\Models\UserModel::currentUser()

Get current user instance



* Visibility: **public**
* This method is **static**.




### setJsonMetaFields

    mixed Chayka\WP\Models\UserModel::setJsonMetaFields(\Chayka\WP\Models\array(string) $metaFields)

Set meta fields that should be populated to json



* Visibility: **public**
* This method is **static**.


#### Arguments
* $metaFields **Chayka\WP\Models\array(string)**



### addJsonMetaField

    mixed Chayka\WP\Models\UserModel::addJsonMetaField(string $fieldName)

Add meta field name that should be populated to json



* Visibility: **public**
* This method is **static**.


#### Arguments
* $fieldName **string**



### removeJsonMetaField

    mixed Chayka\WP\Models\UserModel::removeJsonMetaField(string $fieldName)

Remove meta field name that should not be populated to json



* Visibility: **public**
* This method is **static**.


#### Arguments
* $fieldName **string**



### packJsonItem

    array Chayka\WP\Models\UserModel::packJsonItem()

Packs this user into assoc array for JSON representation.

Used for API Output

* Visibility: **public**




### getValidationErrors

    \Chayka\WP\Models\array[field]='Error Chayka\WP\Models\UserModel::getValidationErrors()

Get validation errors after unpacking from request input
Should be set by validateInput



* Visibility: **public**
* This method is **static**.




### addValidationErrors

    array|mixed Chayka\WP\Models\UserModel::addValidationErrors($errors)

Add validation errors after unpacking from request input



* Visibility: **public**
* This method is **static**.


#### Arguments
* $errors **mixed**



### unpackJsonItem

    \Chayka\WP\Models\PostModel Chayka\WP\Models\UserModel::unpackJsonItem(array $input)

Unpacks request input.

Used by REST Controllers.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $input **array**



### validateInput

    boolean Chayka\WP\Models\UserModel::validateInput(array $input, \Chayka\WP\Models\UserModel $oldState)

Validates input and sets $validationErrors



* Visibility: **public**
* This method is **static**.


#### Arguments
* $input **array**
* $oldState **[Chayka\WP\Models\UserModel](Chayka-WP-Models-UserModel.md)**



### isAdmin

    boolean Chayka\WP\Models\UserModel::isAdmin()

TODO: revise this function
returns true if the user is administrator



* Visibility: **public**




### flushCache

    mixed Chayka\WP\Models\UserModel::flushCache()

Flush user cache



* Visibility: **public**
* This method is **static**.




### getUserCacheById

    mixed|null Chayka\WP\Models\UserModel::getUserCacheById(integer $id)

Get cached user by id



* Visibility: **public**
* This method is **static**.


#### Arguments
* $id **integer**



### getUserCacheByEmail

    array Chayka\WP\Models\UserModel::getUserCacheByEmail(string $email)

Get cached user by email



* Visibility: **public**
* This method is **static**.


#### Arguments
* $email **string**



### getUserCacheByLogin

    array Chayka\WP\Models\UserModel::getUserCacheByLogin(string $login)

Get cached user by email



* Visibility: **public**
* This method is **static**.


#### Arguments
* $login **string**



### userCan

    mixed Chayka\WP\Helpers\AclReady::userCan(string $privilege, \Chayka\WP\Models\UserModel|null $user)

Check if user has $privilege over the model instance



* Visibility: **public**
* This method is defined by [Chayka\WP\Helpers\AclReady](Chayka-WP-Helpers-AclReady.md)


#### Arguments
* $privilege **string**
* $user **[Chayka\WP\Models\UserModel](Chayka-WP-Models-UserModel.md)|null**


