<?php

namespace Jasny\Meta;

require_once 'support/TypeCastingImpl.php';
require_once 'support/class.php';

/**
 * Tests for Jasny\Meta\TypeCasting.
 * 
 * @package Test
 * @author Arnold Daniels
 */
class TypeCastingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Clean up after executing
     */
    public function tearDown()
    {
        TypeCastingImpl::setType(null);
        parent::tearDown();
    }
    
    /**
     * Test type casting to a string
     */
    public function testCastValueToString()
    {
        TypeCastingImpl::setType('string');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);
        
        $obj->prop = '100';
        $obj->cast();
        $this->assertSame('100', $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame('', $obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame('1', $obj->prop);
        
        $obj->prop = true;
        $obj->cast();
        $this->assertSame('1', $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame('', $obj->prop);
    }
    
    /**
     * Test type casting an object with `__toString` to a string
     */
    public function testCastValueToString_Stringable()
    {
        TypeCastingImpl::setType('string');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = new \MetaTest\FooBar();  // Implement __toString
        $obj->cast();
        $this->assertSame('foo', $obj->prop);
    }
    
    /**
     * Test type casting an DateTime to a string
     */
    public function testCastValueToString_DateTime()
    {
        TypeCastingImpl::setType('string');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = new \DateTime("2014-12-31 23:15 UTC");
        $obj->cast();
        $this->assertSame('2014-12-31T23:15:00+00:00', $obj->prop);
    }
    
    /**
     * Test type casting an array to a string
     */
    public function testCastValueToString_Array()
    {
        TypeCastingImpl::setType('string');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast an array to a string");

        $obj->prop = [10, 20];
        $obj->cast();
    }
    
    /**
     * Test type casting an object to a string
     */
    public function testCastValueToString_Object()
    {
        TypeCastingImpl::setType('string');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a stdClass object to a string");

        $obj->prop = (object)['foo' => 'bar'];
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a string
     */
    public function testCastValueToString_Resource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingImpl::setType('string');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a gd resource to a string");

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }
    
    
    /**
     * Test type casting for boolean
     */
    public function testCastValueToBoolean()
    {
        TypeCastingImpl::setType('boolean');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = true;
        $obj->cast();
        $this->assertSame(true, $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame(false, $obj->prop);

        foreach ([1, -1, 10, '1', 'true', 'yes', 'on'] as $value) {
            $obj->prop = $value;
            $obj->cast();
            $this->assertSame(true, $obj->prop, $value);
        }

        foreach ([0, '', '0', 'false', 'no', 'off'] as $value) {
            $obj->prop = $value;
            $obj->cast();
            $this->assertSame(false, $obj->prop, $value);
        }
    }
    
    /**
     * Test type casting an array to a boolean
     */
    public function testCastValueToBoolean_Array()
    {
        TypeCastingImpl::setType('boolean');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast an array to a boolean");

        $obj->prop = [10, 20];
        $obj->cast();
    }
    
    /**
     * Test type casting an object to a boolean
     */
    public function testCastValueToBoolean_Object()
    {
        TypeCastingImpl::setType('boolean');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a stdClass object to a boolean");

        $obj->prop = (object)['foo' => 'bar'];
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a boolean
     */
    public function testCastValueToBoolean_Resource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingImpl::setType('boolean');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a gd resource to a boolean");

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }
    
    /**
     * Test type casting for bool (alias for boolean)
     */
    public function testCastValueToBool()
    {
        TypeCastingImpl::setType('bool');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);
        
        $obj->prop = true;
        $obj->cast();
        $this->assertSame(true, $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame(false, $obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame(true, $obj->prop);
    }
    
    
    /**
     * Test type casting for integer
     */
    public function testCastValueToInteger()
    {
        TypeCastingImpl::setType('integer');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame(1, $obj->prop);
        
        $obj->prop = 0;
        $obj->cast();
        $this->assertSame(0, $obj->prop);
        
        $obj->prop = 10.44;
        $obj->cast();
        $this->assertSame(10, $obj->prop);
        
        $obj->prop = true;
        $obj->cast();
        $this->assertSame(1, $obj->prop);
        
        $obj->prop = '100';
        $obj->cast();
        $this->assertSame(100, $obj->prop);
        
        $obj->prop = '-100.4';
        $obj->cast();
        $this->assertSame(-100, $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame(0, $obj->prop);
    }
    
    /**
     * Test type casting an array to a integer
     */
    public function testCastValueToInteger_Array()
    {
        TypeCastingImpl::setType('integer');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast an array to a integer");

        $obj->prop = [10, 20];
        $obj->cast();
    }
    
    /**
     * Test type casting an object to a integer
     */
    public function testCastValueToInteger_Object()
    {
        TypeCastingImpl::setType('integer');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a stdClass object to a integer");

        $obj->prop = (object)['foo' => 'bar'];
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a integer
     */
    public function testCastValueToInteger_Resource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingImpl::setType('integer');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a gd resource to a integer");

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }
    
    /**
     * Test type casting for int (alias of integer)
     */
    public function testCastValueToInt()
    {
        TypeCastingImpl::setType('int');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame(1, $obj->prop);
        
        $obj->prop = 10.44;
        $obj->cast();
        $this->assertSame(10, $obj->prop);
        
        $obj->prop = '100';
        $obj->cast();
        $this->assertSame(100, $obj->prop);
    }
    
    
    /**
     * Test type casting for float
     */
    public function testCastValueToFloat()
    {
        TypeCastingImpl::setType('float');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = 10.44;
        $obj->cast();
        $this->assertSame(10.44, $obj->prop);

        $obj->prop = INF;
        $obj->cast();
        $this->assertSame(INF, $obj->prop);
        
        $obj->prop = 1;
        $obj->cast();
        $this->assertSame(1.0, $obj->prop);
        
        $obj->prop = true;
        $obj->cast();
        $this->assertSame(1.0, $obj->prop);
        
        $obj->prop = '100';
        $obj->cast();
        $this->assertSame(100.0, $obj->prop);
        
        $obj->prop = '10.44';
        $obj->cast();
        $this->assertSame(10.44, $obj->prop);
        
        $obj->prop = '-10.44';
        $obj->cast();
        $this->assertSame(-10.44, $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame(0.0, $obj->prop);
    }
    
    /**
     * Test type casting an array to a float
     */
    public function testCastValueToFloat_Array()
    {
        TypeCastingImpl::setType('float');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast an array to a float");

        $obj->prop = [10, 20];
        $obj->cast();
    }
    
    /**
     * Test type casting an object to a float
     */
    public function testCastValueToFloat_Object()
    {
        TypeCastingImpl::setType('float');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a stdClass object to a float");

        $obj->prop = (object)['foo' => 'bar'];
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a float
     */
    public function testCastValueToFloat_Resource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingImpl::setType('float');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a gd resource to a float");

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }
    
    
    /**
     * Test type casting for array
     */
    public function testCastValueToArray()
    {
        TypeCastingImpl::setType('array');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = [1, 20, 300];
        $obj->cast();
        $this->assertSame([1, 20, 300], $obj->prop);

        $obj->prop = ['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertSame(['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = (object)['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertSame(['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = 20;
        $obj->cast();
        $this->assertSame([20], $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame([false], $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame([], $obj->prop);
        
        $obj->prop = 'foo';
        $obj->cast();
        $this->assertSame(['foo'], $obj->prop);
        
        $obj->prop = '100, 30, 40';
        $obj->cast();
        $this->assertSame(['100, 30, 40'], $obj->prop);
    }
    
    /**
     * Test type casting an resource to a array
     */
    public function testCastValueToArray_Resource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingImpl::setType('array');
        
        $obj = new TypeCastingImpl();

        $resource = imagecreate(10, 10);
        
        $obj->prop = $resource;
        $obj->cast();
        $this->assertSame([$resource], $obj->prop);
    }

    
    /**
     * Test type casting for object
     */
    public function testCastValueToObject()
    {
        TypeCastingImpl::setType('object');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = (object)['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertInternalType('object', $obj->prop);
        $this->assertEquals((object)['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = [1, 20, 300];
        $obj->cast();
        $this->assertInternalType('object', $obj->prop);
        $this->assertEquals((object)['0' => 1, '1' => 20, '2' => 300], $obj->prop);

        $obj->prop = ['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertInternalType('object', $obj->prop);
        $this->assertEquals((object)['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
    }
    
    /**
     * Test the notice when type casting a scalar value to an object
     */
    public function testCastValueToObject_Scalar()
    {
        TypeCastingImpl::setType('object');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a string to an object");
        
        $obj->prop = 'foo';
        $obj->cast();
    }
    
    /**
     * Test type casting an resource to a object
     */
    public function testCastValueToObject_Resource()
    {
        if (!function_exists('imagecreate')) $this->markTestSkipped("GD not available. Using gd resource for test.");
        
        TypeCastingImpl::setType('object');
        
        $obj = new TypeCastingImpl();

        $this->setExpectedException('PHPUnit_Framework_Error_Warning', "Unable to cast a gd resource to an object");

        $obj->prop = imagecreate(10, 10);
        $obj->cast();
    }

    
    /**
     * Test type casting for DateTime
     */
    public function testDateTime()
    {
        TypeCastingImpl::setType('DateTime');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = '2014-06-01T01:15:00+00:00';
        $obj->cast();
        $this->assertInstanceOf('DateTime', $obj->prop);
        $this->assertSame('2014-06-01T01:15:00+00:00', $obj->prop->format('c'));
    }
    
    /**
     * Test type casting for custom class
     */
    public function testClass()
    {
        TypeCastingImpl::setType('MetaTest\FooBar');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = 22;
        $obj->cast();
        $this->assertInstanceOf('MetaTest\FooBar', $obj->prop);
        $this->assertSame(22, $obj->prop->x);
    }
    
    /**
     * Test the exception when type casting for custom class
     */
    public function testClass_Exception()
    {
        TypeCastingImpl::setType('MetaTest\NotExistent');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $this->setExpectedException('Exception', "Invalid type 'MetaTest\NotExistent'");
        
        $obj->prop = 22;
        $obj->cast();
    }
    
    /**
     * Test type casting for typed array
     */
    public function testTypedArray_Int()
    {
        TypeCastingImpl::setType('int[]');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = [];
        $obj->cast();
        $this->assertSame([], $obj->prop);

        $obj->prop = [1, 20, 300];
        $obj->cast();
        $this->assertSame([1, 20, 300], $obj->prop);

        $obj->prop = ['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertSame(['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = (object)['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertSame(['red' => 1, 'green' => 20, 'blue' => 300], $obj->prop);
        
        $obj->prop = ['1', '20.3', '-300'];
        $obj->cast();
        $this->assertSame([1, 20, -300], $obj->prop);

        $obj->prop = 20;
        $obj->cast();
        $this->assertSame([20], $obj->prop);
        
        $obj->prop = false;
        $obj->cast();
        $this->assertSame([0], $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame([], $obj->prop);
    }
    
    /**
     * Test type casting for typed array with a class
     */
    public function testTypedArray_Class()
    {
        TypeCastingImpl::setType('MetaTest\FooBar[]');
        
        $obj = new TypeCastingImpl();
        
        $obj->prop = null;
        $obj->cast();
        $this->assertNull($obj->prop);

        $obj->prop = [];
        $obj->cast();
        $this->assertSame([], $obj->prop);
        
        $obj->prop = '';
        $obj->cast();
        $this->assertSame([], $obj->prop);
        
        $obj->prop = [1, 20, 300];
        $obj->cast();
        $this->assertInternalType('array', $obj->prop);
        $this->assertCount(3, $obj->prop);
        foreach ([1, 20, 300] as $key => $value) {
            $this->assertArrayHasKey($key, $obj->prop);
            $this->assertInstanceOf('MetaTest\FooBar', $obj->prop[$key]);
            $this->assertSame($value, $obj->prop[$key]->x);
        }

        $obj->prop = (object)['red' => 1, 'green' => 20, 'blue' => 300];
        $obj->cast();
        $this->assertInternalType('array', $obj->prop);
        $this->assertCount(3, $obj->prop);
        foreach (['red' => 1, 'green' => 20, 'blue' => 300] as $key => $value) {
            $this->assertArrayHasKey($key, $obj->prop);
            $this->assertInstanceOf('MetaTest\FooBar', $obj->prop[$key]);
            $this->assertSame($value, $obj->prop[$key]->x);
        }
        
        $obj->prop = 20;
        $obj->cast();
        $this->assertInternalType('array', $obj->prop);
        $this->assertCount(1, $obj->prop);
        $this->assertArrayHasKey(0, $obj->prop);
        $this->assertInstanceOf('MetaTest\FooBar', $obj->prop[0]);
        $this->assertSame(20, $obj->prop[0]->x);
    }
}
