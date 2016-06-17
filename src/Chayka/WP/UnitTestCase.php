<?php
/**
 * Chayka.Framework is a framework that enables WordPress development in a MVC/OOP way.
 *
 * More info: https://github.com/chayka/Chayka.Framework
 */

namespace Chayka\WP;

use Chayka\WP\Helpers\JsonHelper;

/**
 * Class UnitTestCase is custom extension of PHPUnit_Framework_TestCase -> WP_UnitTestCase.
 * Implements specific checks for Chayka.Framework.
 *
 * @package Chayka\WP
 */
abstract class UnitTestCase extends \WP_UnitTestCase{

    /**
     * UnitTestCase constructor
     *
     * @param null|string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = ''){
        JsonHelper::dieOnRespond(false);
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Assert api response, check if:
     * - the response is non empty,
     * - response is parseable json
     * - error code matches expected
     *
     * Returns parsed json response if all checks are passed
     *
     * @param string $response
     * @param int $expectedResponseCode
     *
     * @return array
     */
    public static function assertApiResponse($response, $expectedResponseCode = 0){

        self::assertNotEmpty($response, 'API response should be non-empty');

        $data = json_decode($response, true);

        self::assertNotEmpty($data, 'API response should be parseable JSON');

        if($expectedResponseCode === 'ERROR'){
            self::assertNotEmpty($data['code'], 'Unexpected response code');
        }elseif($expectedResponseCode === 'EXCEPTION'){
            self::assertNotEmpty($data['payload']['file'], 'Exception should have file');
            self::assertNotEmpty($data['payload']['line'], 'Exception should have line');
            self::assertNotEmpty($data['payload']['trace'], 'Exception should have trace');
        }

        return $data;
    }

    /**
     * Assert api response, check if:
     * - the response is non empty,
     * - response is parseable json
     * - error code matches expected
     *
     * @param Plugin $appInstance
     * @param array $request
     * @param int|string $expectedResponseCode
     *
     * @return array
     */
    public static function assertApiRequest($appInstance, $request, $expectedResponseCode = 0){
        try{
            $data = $appInstance->processRequest($request);
        }catch(\Exception $e){
            ob_end_clean();
            $data = JsonHelper::encode([
                'payload' => $e,
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ]);
            
        }
        return self::assertApiResponse($data, $expectedResponseCode);
    }

    /**
     * print_r some variable to output stream
     *
     * @param $var
     * @param string $title
     * @param resource $stdout
     */
    public function varDump($var, $title = '', $stdout = STDOUT){
        $dump = print_r($var, true);
        if($title){
            $dump = $title . ': ' .$dump;
        }
        $dump.="\n";
        fwrite($stdout, $dump);
    }

    /**
     * Set up unit test case
     */
    public function setUp(){
        parent::setUp();
        JsonHelper::dieOnRespond(false);
    }

    /**
     * Show errors on test case tear-down (on finish)
     */
    public function tearDown(){
        parent::tearDown();

        /**
         * @var \PHPUnit_Framework_TestFailure[] $errors
         */
        $errors = $this->getTestResultObject()->errors();

        foreach($errors as $error){
            $e = $error->thrownException();
            if($e){
                $this->varDump($error->exceptionToString($e)."StackTrace:\n".$e->getTraceAsString()."\n", 'Thrown Exception');
            }
        }
    }
}