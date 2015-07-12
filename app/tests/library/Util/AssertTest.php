<?php
namespace App\Test\Util;

use App\Util\Assert;

/**
 * Tests assertions
 *
 * @author Nickolay Martynenko <martynk981@gmail.com>
 */
class AssertTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that isInteger returns input if input is a integer
     *
     * @return void
     */
    public function testIsIntegerSuccess()
    {
        $test = 10;
        $this->assertEquals($test, Assert::isInteger($test));
    }

    /**
     * Test that isInteger throws exception if input is not a string
     *
     * @access public
     * @return void
     */
    public function testIsIntegerFails()
    {
        $this->assertException('isInteger', null);
        $this->assertException('isInteger', true);
        $this->assertException('isInteger', false);
        $this->assertException('isInteger', 0.0);
        $this->assertException('isInteger', '');
        $this->assertException('isInteger', new \stdClass());
    }

    /**
     * Test that integer min/max parameters is success
     *
     * @access public
     * @return void
     */
    public function testIdIntegerParametersSuccess()
    {
        $test = 4;
        $this->assertSame($test, Assert::isInteger($test, 0));
        $this->assertSame($test, Assert::isInteger($test, 0, null));
        $this->assertSame($test, Assert::isInteger($test, 0, 4));
        $this->assertSame($test, Assert::isInteger($test, null, 4));
        $this->assertSame($test, Assert::isInteger($test, null, null));
    }

    /**
     * Assert that the call to Assert::$method throws an exception
     *
     * @param string    $method         Name of the method
     * @param mixed     $value          Value
     * @param mixed     $arg1           Optional arg 1
     * @param mixed     $arg2           Optional arg 2
     *
     * @return void
     */
    private function assertException($method, $value, $arg1 = null, $arg2 = null)
    {
        $this->setExpectedException('\InvalidArgumentException');
        call_user_func(array('App\Util\Assert', $method), $value, $arg1, $arg2);
    }
}