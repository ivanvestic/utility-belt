<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Tests\Unit\Crypt;

use IvanVestic\UtilityBelt\Crypt\CryptUtility;
use PHPUnit\Framework\TestCase;

/**
 * Class CryptUtilityTest
 */
class CryptUtilityTest extends TestCase
{
    /**
     *
     */
    public function testSimpleEncryptDecrypt()
    {
        $string = 'Lorem ipsum dolor sit amet, ei numquam noluisse omittantur has...';
        $secretKey = 'NOT_SO_SECRET_TEST_KEY';
        $secretInitVector = 'NOT_SO_SECRET_TEST_INITIALIZATION_VECTOR';

        $encryptedString = CryptUtility::simpleEncrypt($string, $secretKey, $secretInitVector);
        $decryptedString = CryptUtility::simpleDecrypt($encryptedString, $secretKey, $secretInitVector);

        $this->assertTrue(($string === $decryptedString));
    }
}