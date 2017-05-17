<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

use IvanVestic\UtilityBelt\JsonUtility;
use PHPUnit\Framework\TestCase;

class JsonUtilityTest extends TestCase
{

    /**
     *
     */
    public function testIsJson()
    {
        // misc test data in \stdClass format
        $dataObj = new \stdClass();
        $dataObj->data = new \stdClass();
        $dataObj->data->id = 123;
        $dataObj->data->name = 'John Doe';
    
        // same data as above in array format
        $dataArray = ['data' => [
            'id'   => 123,
            'name' => 'John Doe'
        ]];
    
        // '{"data":{"id":123,"name":"John Doe"}}'
        $validJsonString = json_encode($dataObj);
        $this->assertTrue((true === JsonUtility::isJson($validJsonString)));
        $this->assertTrue(($dataObj == JsonUtility::isJson($validJsonString, false)));
        $this->assertTrue(($dataArray === JsonUtility::isJson($validJsonString, true)));
    
        $invalidJsonString = '{"data":{"id":"123","name":"John Doe}}';
        $this->assertTrue((false === JsonUtility::isJson($invalidJsonString)));
        $this->assertTrue((null === JsonUtility::isJson($invalidJsonString, false)));
        $this->assertTrue((null === JsonUtility::isJson($invalidJsonString, true)));
    }
}