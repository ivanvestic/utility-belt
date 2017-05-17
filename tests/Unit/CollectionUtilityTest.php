<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

use IvanVestic\UtilityBelt\CollectionUtility;
use PHPUnit\Framework\TestCase;

class CollectionUtilityTest extends TestCase
{
    /**
     *
     */
    public function testIsCollection()
    {
        $testValue = [];
        $this->assertTrue((true === CollectionUtility::isCollection($testValue)));

        $testValue = new \stdClass();
        $this->assertTrue((true === CollectionUtility::isCollection($testValue)));

        $testValue = new \DateTime();
        $this->assertTrue((true === CollectionUtility::isCollection($testValue)));

        $testValue = new \DateTime();
        $this->assertTrue((gettype($testValue) === CollectionUtility::isCollection($testValue, true)));

        $testValue = '';
        $this->assertTrue((false === CollectionUtility::isCollection($testValue)));

        $testValue = null;
        $this->assertTrue((false === CollectionUtility::isCollection($testValue)));
    }

    /**
     *
     */
    public function testCollectionOverwrite()
    {
        $result = CollectionUtility::collectionOverwrite([], [], false, true);
        $this->assertTrue((is_array($result) && empty($result)));

        $result = CollectionUtility::collectionOverwrite([], [1, 2, 3], false, true);
        $this->assertTrue((is_array($result) && empty($result)));

        // base collection (1 dimensional)
        $collection1 = ['a' => '', 'b' => '', 'c' => ''];

        $collection2 = ['a' => 'apple', 'b' => 'banana', 'c' => 'cherry'];
        $result = CollectionUtility::collectionOverwrite($collection1, $collection2, false, true);
        // expecting: ['a' => 'apple', 'b' => 'banana', 'c' => 'cherry']
        $this->assertTrue((is_array($result) && $result === $collection2));

        $collection2 = ['a' => 'apple', 'c' => 'cherry'];
        $result = CollectionUtility::collectionOverwrite($collection1, $collection2, false, false);
        // expecting: stdClass Object('a' => 'apple', 'c' => 'cherry')
        $this->assertTrue((
            ($result instanceof \stdClass) && count(get_object_vars($result)) == 2 && !property_exists($result, 'b')
        ));

        $collection2 = new \stdClass();
        $result = CollectionUtility::collectionOverwrite($collection1, $collection2, false, true);
        // expecting: []
        $this->assertTrue((is_array($result) && $result === []));

        $collection2 = new \stdClass();
        $result = CollectionUtility::collectionOverwrite($collection1, $collection2, true, true);
        // expecting: ['a' => '', 'b' => '', 'c' => '']
        $this->assertTrue((is_array($result) && $result === $collection1));

        // base collection (3 dimensional)
        $collection1 = [
            'a' => 'x',
            'b' => [
                'a' => 'x',
                'c' => [
                    'a' => 1,
                    'b' => 2,
                    'c' => 3
                ],
                'd' => 'x'
            ],
            'c' => 'x'
        ];
        $collection2 = [
            'a' => 'apple',
            'b' => [
                'c' => [
                    'b' => 'OVERRIDE'
                ]
            ],
            'c' => 'cherry'
        ];
        $result = CollectionUtility::collectionOverwrite($collection1, $collection2, true, true);
        $expectedResult = [
            'a' => 'apple',
            'b' => [
                'a' => 'x',
                'c' => [
                    'a' => 1,
                    'b' => 'OVERRIDE',
                    'c' => 3,
                ],
                'd' => 'x'
            ],
            'c' => 'cherry'
        ];
        // expecting diff to be preserved
        // notice: 'x' values from the original $collection1, are expected to be preserved,
        //         because none of the keys specified in $collection2 match those same keys,
        //         on the same N-level, and also because the $preserveDiff flag is set to true.
        $this->assertTrue((is_array($result) && $result === $expectedResult));

    }
}
