<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Crypt;

use IvanVestic\UtilityBelt\Hash\HashUtility;
use IvanVestic\UtilityBelt\OpenSSL\OpenSSLCipherUtility;

/**
 * Class CryptUtility
 */
class CryptUtility
{
    /**
     * @link http://php.net/manual/en/function.openssl-encrypt.php
     *
     * @param string $string
     * @param string $secretKey
     * @param string $secretInitializationVector
     * @param string $encryptMethod              http://php.net/manual/en/function.openssl-get-cipher-methods.php
     * @param string $hashAlgo                   http://php.net/manual/en/function.hash-algos.php
     * @param int $hashSubStrStart
     * @param int $hashSubStrLength
     * @param int $opensslOptions
     *
     * @return string|null
     */
    public static function simpleEncrypt(
        string $string,
        string $secretKey,
        string $secretInitializationVector,
        string $encryptMethod = OpenSSLCipherUtility::METHOD_AES_256_CBC,
        string $hashAlgo = HashUtility::ALGO_SHA512,
        int $hashSubStrStart = 0,
        int $hashSubStrLength = 16,
        int $opensslOptions = 0
    ) {
        if ('' == $string) {
            return null;
        }

        $key = hash($hashAlgo, $secretKey);
        $initializationVector = substr(hash($hashAlgo, $secretInitializationVector), $hashSubStrStart, $hashSubStrLength);

        // note: as a rough rule of thumb, base64 encoding increases the original data in size by about 33%
        return base64_encode(openssl_encrypt($string, $encryptMethod, $key, $opensslOptions, $initializationVector));
    }

    /**
     * @link http://php.net/manual/en/function.openssl-decrypt.php
     *
     * @param string $string
     * @param string $secretKey
     * @param string $secretInitializationVector
     * @param string $decryptMethod              http://php.net/manual/en/function.openssl-get-cipher-methods.php
     * @param string $hashAlgo                   http://php.net/manual/en/function.hash-algos.php
     * @param int $hashSubStrStart
     * @param int $hashSubStrLength
     * @param int $opensslOptions
     *
     * @return string|false|null
     */
    public static function simpleDecrypt(
        string $string,
        string $secretKey,
        string $secretInitializationVector,
        string $decryptMethod = OpenSSLCipherUtility::METHOD_AES_256_CBC,
        string $hashAlgo = HashUtility::ALGO_SHA512,
        int $hashSubStrStart = 0,
        int $hashSubStrLength = 16,
        int $opensslOptions = 0
    ) {
        if ('' == $string) {
            return null;
        }

        $key = hash($hashAlgo, $secretKey);
        $initializationVector = substr(hash($hashAlgo, $secretInitializationVector), $hashSubStrStart, $hashSubStrLength);

        return openssl_decrypt(base64_decode($string), $decryptMethod, $key, $opensslOptions, $initializationVector);
    }
}