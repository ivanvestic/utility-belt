<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

use IvanVestic\UtilityBelt\DateTimeUtility;
use PHPUnit\Framework\TestCase;

class DateTimeUtilityTest extends TestCase
{

    /**
     *
     */
    public function testGetDateTime()
    {
        // valid
        $testValue = '';
        $datetime = DateTimeUtility::getDateTime($testValue, true, false);
        $this->assertTrue(($datetime instanceof \DateTime));

        // valid
        $testValue = '';
        $datetime = DateTimeUtility::getDateTime($testValue, true, true);
        $this->assertTrue(($datetime instanceof \DateTime));

        // invalid
        $testValue = 'x';
        $datetime = DateTimeUtility::getDateTime($testValue, true, false);
        $this->assertTrue((false === $datetime));

        // invalid
        $testValue = 'x';
        $datetime = DateTimeUtility::getDateTime($testValue, true, true);
        $this->assertTrue(is_array($datetime));
    }

    /**
     *
     */
    public function testIsZeroDateTime()
    {
        $testValue = '';
        $this->assertTrue((false === DateTimeUtility::isZeroDateTime($testValue)));

        $testValue = 0;
        $this->assertTrue((false === DateTimeUtility::isZeroDateTime($testValue)));

        $testValue = '0000-00-00 00:00:00';
        $this->assertTrue((true === DateTimeUtility::isZeroDateTime($testValue)));

        $testValue = new \DateTime('0000-00-00 00:00:00');
        $this->assertTrue((true === DateTimeUtility::isZeroDateTime($testValue)));

        $testValue = new \DateTime('0000-00-00 00:00:01');
        $this->assertTrue((false === DateTimeUtility::isZeroDateTime($testValue)));
    }
}
