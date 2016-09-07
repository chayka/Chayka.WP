Chayka\WP\Helpers\EncryptionHelper
===============

Class EncryptionHelper provides methods to encrypt and decrypt data.




* Class name: EncryptionHelper
* Namespace: Chayka\WP\Helpers
* Parent class: Chayka\Helpers\EncryptionHelper







Methods
-------


### encrypt

    string Chayka\WP\Helpers\EncryptionHelper::encrypt(string $value, string $key, string $cipher, string $mode)

Encrypt provided data.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $value **string**
* $key **string**
* $cipher **string**
* $mode **string**



### decrypt

    string Chayka\WP\Helpers\EncryptionHelper::decrypt(string $value, string $key, string $cipher, string $mode)

Decrypt provided data.

If decryption failed, returns initial data.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $value **string**
* $key **string**
* $cipher **string**
* $mode **string**


