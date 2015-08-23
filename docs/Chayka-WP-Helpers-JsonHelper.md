Chayka\WP\Helpers\JsonHelper
===============

Class JsonHelper wraps all the api output into an envelope

{
 payload: mixed,
 message: string,
 code: string|int
}

Payload is scanned recursively for JsonReady interface instances,
so that json representaion of the object can be customized

This class differs from it's base class by the ability to pack WP_Errors object


* Class name: JsonHelper
* Namespace: Chayka\WP\Helpers
* Parent class: Chayka\Helpers\JsonHelper







Methods
-------


### respondErrors

    mixed Chayka\WP\Helpers\JsonHelper::respondErrors(\Chayka\WP\Helpers\array/WP_Error $errors, mixed $payload, integer $httpResponseCode)

Wrap multiple errors into {'payload': .

.., 'code': ..., 'message': ...} envelope.
Set http response code to $httpResponseCode = 400.
And then die() it.

* Visibility: **public**
* This method is **static**.


#### Arguments
* $errors **Chayka\WP\Helpers\array/WP_Error**
* $payload **mixed**
* $httpResponseCode **integer**



### packWpErrors

    array Chayka\WP\Helpers\JsonHelper::packWpErrors(\WP_Error $errors)

Wrap WP_Errors object into assoc array.



* Visibility: **public**
* This method is **static**.


#### Arguments
* $errors **WP_Error**


