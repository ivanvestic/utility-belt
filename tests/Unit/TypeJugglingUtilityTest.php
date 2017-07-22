<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Tests\Unit;

use IvanVestic\UtilityBelt\TypeJugglingUtility;
use PHPUnit\Framework\TestCase;

/**
 * Class TypeJugglingUtilityTest
 */
class TypeJugglingUtilityTest extends TestCase
{

    /**
     * @test
     */
    public function getTrueTypeValue()
    {
        // bool
        $value = 'true';
        $this->assertTrue(is_bool(TypeJugglingUtility::getTrueTypeValue($value)));
        $value = 'false';
        $this->assertTrue(is_bool(TypeJugglingUtility::getTrueTypeValue($value)));

        // float/double
        $value = '1.2';
        $this->assertTrue((
            is_float(TypeJugglingUtility::getTrueTypeValue($value))
            && is_double(TypeJugglingUtility::getTrueTypeValue($value))
        ));

        // int
        $value = '0';
        $this->assertTrue(is_int(TypeJugglingUtility::getTrueTypeValue($value)));

        // string
        $value = '1,1';
        $this->assertTrue(is_string(TypeJugglingUtility::getTrueTypeValue($value)));
    }

    /**
     *
     */
    public function testConvertArrayToObject()
    {
        // original
        $array = [];
        // expected result
        $expectedObject = new \stdClass();
        // the test
        $this->assertTrue(($expectedObject == TypeJugglingUtility::convertCollectionToObject($array, false)));

        // original
        $array = ['a', 'b', 'c'];
        // expected result
        $expectedObject = new \stdClass();
        $expectedObject->{0} = 'a';
        $expectedObject->{1} = 'b';
        $expectedObject->{2} = 'c';
        // the test
        $this->assertTrue(($expectedObject == TypeJugglingUtility::convertCollectionToObject($array, false)));

        // original
        $array = [
            'a' => 'apple',
            'b' => 'banana',
            'c' => ['a' => 1, 'b' => 2],
            'd' => 'durian'
        ];
        // expected result
        $expectedObject = new \stdClass();
        $expectedObject->a = 'apple';
        $expectedObject->b = 'banana';
        $expectedObject->c = new \stdClass();
        $expectedObject->c->a = 1;
        $expectedObject->c->b = 2;
        $expectedObject->d = 'durian';
        // the test
        $this->assertTrue(($expectedObject == TypeJugglingUtility::convertCollectionToObject($array, false)));

        // original
        $array = [
            'a' => 'apple',
            'b' => 'banana',
            'c' => ['a' => 1, 'b' => 2],
            'd' => 'durian',
            'e' => ['a' => 1, 'c' => 2]
        ];
        // blacklist
        $blacklist = ['c'];
        // expected result
        $expectedObject = new \stdClass();
        $expectedObject->a = 'apple';
        $expectedObject->b = 'banana';
        $expectedObject->d = 'durian';
        $expectedObject->e = new \stdClass();
        $expectedObject->e->a = 1;
        // the test
        $this->assertTrue(($expectedObject == TypeJugglingUtility::convertCollectionToObject($array, false, $blacklist)));

        // original
        $array = [
            'a' => 'apple',
            'b' => 'banana',
            'c' => ['a' => 1, 'b' => 2],
            'd' => 'durian',
            'e' => ['a' => 1, 'c' => 2]
        ];
        // blacklist
        $blacklist = ['a'];
        // expected result
        $expectedObject = new \stdClass();
        $expectedObject->b = 'banana';
        $expectedObject->c = new \stdClass();
        $expectedObject->c->b = 2;
        $expectedObject->d = 'durian';
        $expectedObject->e = new \stdClass();
        $expectedObject->e->c = 2;
        // the test
        $this->assertTrue(($expectedObject == TypeJugglingUtility::convertCollectionToObject($array, false, $blacklist)));

        // original
        $array = [
            'a' => 'apple',
            'b' => 'banana',
            'c' => ['a' => 1, 'b' => 2],
            'd' => 'durian',
            'e' => ['a' => 1, 'c' => 2]
        ];
        // blacklist
        $blacklist = ['a' => true, 'c' => false];
        // expected result
        $expectedObject = new \stdClass();
        $expectedObject->b = 'banana';
        $expectedObject->c = new \stdClass();
        $expectedObject->c->b = 2;
        $expectedObject->d = 'durian';
        $expectedObject->e = new \stdClass();
        $expectedObject->e->c = 2;
        // the test
        $this->assertTrue(($expectedObject == TypeJugglingUtility::convertCollectionToObject($array, false, $blacklist, true)));
    }

    /**
     *
     */
    public function testConvertObjectToArray()
    {
        // original
        $object = new \stdClass();
        // expected result
        $expectedArray = [];
        // the test
        $this->assertTrue(($expectedArray == TypeJugglingUtility::convertCollectionToArray($object, false)));

        // original
        $object = new \stdClass();
        $object->{0} = 'a';
        $object->{1} = 'b';
        $object->{2} = 'c';
        // expected result
        $expectedArray = ['a', 'b', 'c'];
        // the test
        $this->assertTrue(($expectedArray == TypeJugglingUtility::convertCollectionToArray($object, false)));

        // original
        $object = new \stdClass();
        $object->a = 'apple';
        $object->b = 'banana';
        $object->c = new \stdClass();
        $object->c->a = 1;
        $object->c->b = 2;
        $object->d = 'durian';
        // expected result
        $expectedArray = [
            'a' => 'apple',
            'b' => 'banana',
            'c' => ['a' => 1, 'b' => 2],
            'd' => 'durian'
        ];
        // the test
        $this->assertTrue(($expectedArray == TypeJugglingUtility::convertCollectionToArray($object, false)));

        // original
        $object = new \stdClass();
        $object->a = 'apple';
        $object->b = 'banana';
        $object->c = new \stdClass();
        $object->c->a = 1;
        $object->c->b = 2;
        $object->d = 'durian';
        $object->e = new \stdClass();
        $object->e->a = 1;
        $object->e->c = 2;
        // blacklist
        $blacklist = ['c'];
        // expected result
        $expectedArray = [
            'a' => 'apple',
            'b' => 'banana',
            'd' => 'durian',
            'e' => ['a' => 1]
        ];
        // the test
        $this->assertTrue(($expectedArray == TypeJugglingUtility::convertCollectionToArray($object, false, $blacklist)));

        // original
        $object = new \stdClass();
        $object->a = 'apple';
        $object->b = 'banana';
        $object->c = new \stdClass();
        $object->c->a = 1;
        $object->c->b = 2;
        $object->d = 'durian';
        $object->e = new \stdClass();
        $object->e->a = 1;
        $object->e->c = 2;
        // blacklist
        $blacklist = ['a'];
        // expected result
        $expectedArray = [
            'b' => 'banana',
            'c' => ['b' => 2],
            'd' => 'durian',
            'e' => ['c' => 2]
        ];
        // the test
        $this->assertTrue(($expectedArray == TypeJugglingUtility::convertCollectionToArray($object, false, $blacklist)));

        // original
        $object = new \stdClass();
        $object->a = 'apple';
        $object->b = 'banana';
        $object->c = new \stdClass();
        $object->c->a = 1;
        $object->c->b = 2;
        $object->d = 'durian';
        $object->e = new \stdClass();
        $object->e->a = 1;
        $object->e->c = 2;
        // blacklist
        $blacklist = ['a' => true, 'c' => false];
        // expected result
        $expectedArray = [
            'b' => 'banana',
            'c' => ['b' => 2],
            'd' => 'durian',
            'e' => ['c' => 2]
        ];
        // the test
        $this->assertTrue(($expectedArray == TypeJugglingUtility::convertCollectionToArray($object, false, $blacklist, true)));

        // original
        $object = new \stdClass();
        $object->a = 'apple';
        $object->b = 'banana';
        $object->c = new \stdClass();
        $object->c->a = 1;
        $object->c->b = 2;
        $object->d = 'durian';
        $object->e = new \stdClass();
        $object->e->a = 1;
        $object->e->c = 2;
        // the test
        $this->assertTrue((TypeJugglingUtility::convertCollectionToArray(TypeJugglingUtility::convertCollectionToObject($object)) === TypeJugglingUtility::convertCollectionToArray($object)));

    }
}
