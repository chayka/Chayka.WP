Chayka\WP\Helpers\OptionHelper
===============

Class OptionHelper is a wrapper for:
- get_option
- update_option
- get_site_option
- update_site_option

As a bonus it stores all options with suffix provided by getSuffix()

You can create your own OptionHelper by extending this one.
By default getSuffix() creates suffix based on class namespace.
So you can just extend OptionHelper with different namespace
and empty class, to get custom prefix.

Another bonus is that all options are cached.


* Class name: OptionHelper
* Namespace: Chayka\WP\Helpers





Properties
----------


### $cache

    protected array $cache = array()

Options cache, not to load them twice.



* Visibility: **protected**
* This property is **static**.


Methods
-------


### getPrefix

    string Chayka\WP\Helpers\OptionHelper::getPrefix()

You can ascend from this class.

You may want to override this class to set custom prefix.

* Visibility: **public**
* This method is **static**.




### getOption

    mixed|void Chayka\WP\Helpers\OptionHelper::getOption(string $option, string $default, boolean $reload)

Alias to get_option but with custom prefix



* Visibility: **public**
* This method is **static**.


#### Arguments
* $option **string**
* $default **string**
* $reload **boolean**



### setOption

    boolean Chayka\WP\Helpers\OptionHelper::setOption(string $option, $value)

Alias to update_option but with custom prefix



* Visibility: **public**
* This method is **static**.


#### Arguments
* $option **string**
* $value **mixed**



### getSiteOption

    mixed Chayka\WP\Helpers\OptionHelper::getSiteOption($option, string $default, boolean $reload)

Alias to get_site_option but with custom prefix



* Visibility: **public**
* This method is **static**.


#### Arguments
* $option **mixed**
* $default **string**
* $reload **boolean**



### setSiteOption

    boolean Chayka\WP\Helpers\OptionHelper::setSiteOption($option, $value)

Alias to get_site_option but with custom prefix



* Visibility: **public**
* This method is **static**.


#### Arguments
* $option **mixed**
* $value **mixed**



### encrypt

    string Chayka\WP\Helpers\OptionHelper::encrypt($value, string $key)

Encrypt provided data.

Encrypts with NONCE_KEY constant as a key by default

* Visibility: **public**
* This method is **static**.


#### Arguments
* $value **mixed**
* $key **string**



### decrypt

    string Chayka\WP\Helpers\OptionHelper::decrypt($value, string $key)

Decrypt provided data.

Decrypts with NONCE_KEY constant as a key by default.
If decryption failed, returns initial data.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $value **mixed**
* $key **string**



### getEncryptedOption

    mixed|void Chayka\WP\Helpers\OptionHelper::getEncryptedOption(string $option, string $default, boolean $reload)

Get previously encrypted and stored option (with custom prefix)



* Visibility: **public**
* This method is **static**.


#### Arguments
* $option **string**
* $default **string**
* $reload **boolean**



### setEncryptedOption

    boolean Chayka\WP\Helpers\OptionHelper::setEncryptedOption(string $option, $value)

Encrypt and store option



* Visibility: **public**
* This method is **static**.


#### Arguments
* $option **string**
* $value **mixed**



### getEncryptedSiteOption

    mixed Chayka\WP\Helpers\OptionHelper::getEncryptedSiteOption($option, string $default, boolean $reload)

Get previously encrypted and stored site option (with custom prefix)



* Visibility: **public**
* This method is **static**.


#### Arguments
* $option **mixed**
* $default **string**
* $reload **boolean**



### setEncryptedSiteOption

    boolean Chayka\WP\Helpers\OptionHelper::setEncryptedSiteOption($option, $value)

Encrypt and store site option (with custom prefix)



* Visibility: **public**
* This method is **static**.


#### Arguments
* $option **mixed**
* $value **mixed**


