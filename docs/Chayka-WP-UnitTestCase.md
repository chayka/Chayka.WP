Chayka\WP\UnitTestCase
===============

Class UnitTestCase is custom extension of PHPUnit_Framework_TestCase -&gt; WP_UnitTestCase.

Implements specific checks for Chayka.Framework.


* Class name: UnitTestCase
* Namespace: Chayka\WP
* This is an **abstract** class
* Parent class: WP_UnitTestCase







Methods
-------


### __construct

    mixed Chayka\WP\UnitTestCase::__construct(null|string $name, array $data, string $dataName)

UnitTestCase constructor



* Visibility: **public**


#### Arguments
* $name **null|string**
* $data **array**
* $dataName **string**



### assertApiResponse

    array Chayka\WP\UnitTestCase::assertApiResponse(string $response, integer $expectedResponseCode)

Assert api response, check if:
- the response is non empty,
- response is parseable json
- error code matches expected

Returns parsed json response if all checks are passed

* Visibility: **public**
* This method is **static**.


#### Arguments
* $response **string**
* $expectedResponseCode **integer**



### assertApiRequest

    array Chayka\WP\UnitTestCase::assertApiRequest(\Chayka\WP\Plugin $appInstance, array $request, integer|string $expectedResponseCode)

Assert api response, check if:
- the response is non empty,
- response is parseable json
- error code matches expected



* Visibility: **public**
* This method is **static**.


#### Arguments
* $appInstance **[Chayka\WP\Plugin](Chayka-WP-Plugin.md)**
* $request **array**
* $expectedResponseCode **integer|string**



### varDump

    mixed Chayka\WP\UnitTestCase::varDump($var, string $title, resource $stdout)

print_r some variable to output stream



* Visibility: **public**


#### Arguments
* $var **mixed**
* $title **string**
* $stdout **resource**



### setUp

    mixed Chayka\WP\UnitTestCase::setUp()

Set up unit test case



* Visibility: **public**




### tearDown

    mixed Chayka\WP\UnitTestCase::tearDown()

Show errors on test case tear-down (on finish)



* Visibility: **public**



