Chayka\WP\Helpers\CacheHelper
===============

Class CacheHelper enables custom smart caching:

$a = 'John'

$value = CacheHelper::_('some cache', DAY_IN_SECONDS, function($key) use ($a){
     return "Hello $a";
});

$value = CacheHelper::__('some multi-site cache', DAY_IN_SECONDS, function($key) use ($a){
     return "Hello multi-site $a";
});


* Class name: CacheHelper
* Namespace: Chayka\WP\Helpers







Methods
-------


### _

    mixed Chayka\WP\Helpers\CacheHelper::_(string $key, integer $expiration, callable|\Closure $default)

Returns cached value if one exists or default one.

Callback or Closure can be passed as $default like this:

$a = 'John'

$value = CacheHelper::_('some cache', DAY_IN_SECONDS, function($key) use ($a){
     return "Hello $a";
});

* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**
* $expiration **integer**
* $default **callable|Closure**



### getValue

    mixed Chayka\WP\Helpers\CacheHelper::getValue(string $key, string|callable|\Closure $default, integer $expiration)

Returns cached value if one exists or default one.

Callback or Closure can be passed as $default like this:

$a = 'John'

$value = CacheHelper::getValue('some cache', function($key) use ($a){
     return "Hello $a";
}, DAY_IN_SECONDS);

* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**
* $default **string|callable|Closure**
* $expiration **integer**



### setValue

    boolean Chayka\WP\Helpers\CacheHelper::setValue(string $key, mixed $value, integer $expiration)

Store value in cache



* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**
* $value **mixed**
* $expiration **integer**



### deleteValue

    boolean Chayka\WP\Helpers\CacheHelper::deleteValue(string $key)

Delete stored value



* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**



### flushValue

    boolean Chayka\WP\Helpers\CacheHelper::flushValue(string $key)

Flush stored value from memory



* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**



### __

    mixed Chayka\WP\Helpers\CacheHelper::__(string $key, integer $expiration, callable|\Closure $default)

Returns cached value if one exists or default one.

Callback or Closure can be passed as $default like this:

$a = 'John'

$value = CacheHelper::_('some cache', DAY_IN_SECONDS, function($key) use ($a){
     return "Hello $a";
});

* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**
* $expiration **integer**
* $default **callable|Closure**



### getSiteValue

    mixed Chayka\WP\Helpers\CacheHelper::getSiteValue(string $key, string|callable|\Closure $default, integer $expiration)

Returns cached value if one exists or default one.

Callback or Closure can be passed as $default like this:

$a = 'John'

$value = CacheHelper::getValue('some cache', function($key) use ($a){
     return "Hello $a";
}, DAY_IN_SECONDS);

* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**
* $default **string|callable|Closure**
* $expiration **integer**



### setSiteValue

    boolean Chayka\WP\Helpers\CacheHelper::setSiteValue(string $key, mixed $value, integer $expiration)

Store value in cache



* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**
* $value **mixed**
* $expiration **integer**



### deleteSiteValue

    boolean Chayka\WP\Helpers\CacheHelper::deleteSiteValue(string $key)

Delete stored value



* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**



### flushSiteValue

    boolean Chayka\WP\Helpers\CacheHelper::flushSiteValue(string $key)

Flush stored value from memory



* Visibility: **public**
* This method is **static**.


#### Arguments
* $key **string**


