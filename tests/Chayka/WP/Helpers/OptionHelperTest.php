<?php
/**
 * Created by PhpStorm.
 * User: borismossounov
 * Date: 12.10.14
 * Time: 11:07
 */

namespace MyNamespace{

    use Chayka\WP\Helpers;

    class OptionHelper extends Helpers\OptionHelper{

    }
}

namespace MyNamespace\Core\Helpers{

    use Chayka\WP\Helpers;

    class OptionHelper extends Helpers\OptionHelper{

    }
}

namespace {

    use MyNamespace\OptionHelper as OptionHelper1;
    use MyNamespace\Core\Helpers\OptionHelper as OptionHelper2;

    class OptionHelperTest extends PHPUnit_Framework_TestCase {

        public function testPrefixGeneration(){
            $this->assertEquals('MyNamespace.', OptionHelper1::getPrefix());
            $this->assertEquals('MyNamespace.Core.', OptionHelper2::getPrefix());
        }

    }
}
