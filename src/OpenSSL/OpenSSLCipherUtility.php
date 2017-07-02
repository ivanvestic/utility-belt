<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\OpenSSL;


/**
 * Class OpenSSLCipherUtility
 */
class OpenSSLCipherUtility
{
    // these openssl cipher methods  were extracted as constants for convenience
    // from openssl_get_cipher_methods() php v7.0.15 'OpenSSL 1.0.1e-fips 11 Feb 2013'
    // @link http://php.net/manual/en/function.openssl-get-cipher-methods.php
    const METHOD_AES_128_CBC = 'AES-128-CBC';
    const METHOD_AES_128_CBC_HMAC_SHA1 = 'AES-128-CBC-HMAC-SHA1';
    const METHOD_AES_128_CFB = 'AES-128-CFB';
    const METHOD_AES_128_CFB1 = 'AES-128-CFB1';
    const METHOD_AES_128_CFB8 = 'AES-128-CFB8';
    const METHOD_AES_128_CTR = 'AES-128-CTR';
    const METHOD_AES_128_ECB = 'AES-128-ECB';
    const METHOD_AES_128_OFB = 'AES-128-OFB';
    const METHOD_AES_128_XTS = 'AES-128-XTS';
    const METHOD_AES_192_CBC = 'AES-192-CBC';
    const METHOD_AES_192_CFB = 'AES-192-CFB';
    const METHOD_AES_192_CFB1 = 'AES-192-CFB1';
    const METHOD_AES_192_CFB8 = 'AES-192-CFB8';
    const METHOD_AES_192_CTR = 'AES-192-CTR';
    const METHOD_AES_192_ECB = 'AES-192-ECB';
    const METHOD_AES_192_OFB = 'AES-192-OFB';
    const METHOD_AES_256_CBC = 'AES-256-CBC';
    const METHOD_AES_256_CBC_HMAC_SHA1 = 'AES-256-CBC-HMAC-SHA1';
    const METHOD_AES_256_CFB = 'AES-256-CFB';
    const METHOD_AES_256_CFB1 = 'AES-256-CFB1';
    const METHOD_AES_256_CFB8 = 'AES-256-CFB8';
    const METHOD_AES_256_CTR = 'AES-256-CTR';
    const METHOD_AES_256_ECB = 'AES-256-ECB';
    const METHOD_AES_256_OFB = 'AES-256-OFB';
    const METHOD_AES_256_XTS = 'AES-256-XTS';
    const METHOD_BF_CBC = 'BF-CBC';
    const METHOD_BF_CFB = 'BF-CFB';
    const METHOD_BF_ECB = 'BF-ECB';
    const METHOD_BF_OFB = 'BF-OFB';
    const METHOD_CAMELLIA_128_CBC = 'CAMELLIA-128-CBC';
    const METHOD_CAMELLIA_128_CFB = 'CAMELLIA-128-CFB';
    const METHOD_CAMELLIA_128_CFB1 = 'CAMELLIA-128-CFB1';
    const METHOD_CAMELLIA_128_CFB8 = 'CAMELLIA-128-CFB8';
    const METHOD_CAMELLIA_128_ECB = 'CAMELLIA-128-ECB';
    const METHOD_CAMELLIA_128_OFB = 'CAMELLIA-128-OFB';
    const METHOD_CAMELLIA_192_CBC = 'CAMELLIA-192-CBC';
    const METHOD_CAMELLIA_192_CFB = 'CAMELLIA-192-CFB';
    const METHOD_CAMELLIA_192_CFB1 = 'CAMELLIA-192-CFB1';
    const METHOD_CAMELLIA_192_CFB8 = 'CAMELLIA-192-CFB8';
    const METHOD_CAMELLIA_192_ECB = 'CAMELLIA-192-ECB';
    const METHOD_CAMELLIA_192_OFB = 'CAMELLIA-192-OFB';
    const METHOD_CAMELLIA_256_CBC = 'CAMELLIA-256-CBC';
    const METHOD_CAMELLIA_256_CFB = 'CAMELLIA-256-CFB';
    const METHOD_CAMELLIA_256_CFB1 = 'CAMELLIA-256-CFB1';
    const METHOD_CAMELLIA_256_CFB8 = 'CAMELLIA-256-CFB8';
    const METHOD_CAMELLIA_256_ECB = 'CAMELLIA-256-ECB';
    const METHOD_CAMELLIA_256_OFB = 'CAMELLIA-256-OFB';
    const METHOD_CAST5_CBC = 'CAST5-CBC';
    const METHOD_CAST5_CFB = 'CAST5-CFB';
    const METHOD_CAST5_ECB = 'CAST5-ECB';
    const METHOD_CAST5_OFB = 'CAST5-OFB';
    const METHOD_DES_CBC = 'DES-CBC';
    const METHOD_DES_CFB = 'DES-CFB';
    const METHOD_DES_CFB1 = 'DES-CFB1';
    const METHOD_DES_CFB8 = 'DES-CFB8';
    const METHOD_DES_ECB = 'DES-ECB';
    const METHOD_DES_EDE = 'DES-EDE';
    const METHOD_DES_EDE_CBC = 'DES-EDE-CBC';
    const METHOD_DES_EDE_CFB = 'DES-EDE-CFB';
    const METHOD_DES_EDE_OFB = 'DES-EDE-OFB';
    const METHOD_DES_EDE3 = 'DES-EDE3';
    const METHOD_DES_EDE3_CBC = 'DES-EDE3-CBC';
    const METHOD_DES_EDE3_CFB = 'DES-EDE3-CFB';
    const METHOD_DES_EDE3_CFB1 = 'DES-EDE3-CFB1';
    const METHOD_DES_EDE3_CFB8 = 'DES-EDE3-CFB8';
    const METHOD_DES_EDE3_OFB = 'DES-EDE3-OFB';
    const METHOD_DES_OFB = 'DES-OFB';
    const METHOD_DESX_CBC = 'DESX-CBC';
    const METHOD_IDEA_CBC = 'IDEA-CBC';
    const METHOD_IDEA_CFB = 'IDEA-CFB';
    const METHOD_IDEA_ECB = 'IDEA-ECB';
    const METHOD_IDEA_OFB = 'IDEA-OFB';
    const METHOD_RC2_40_CBC = 'RC2-40-CBC';
    const METHOD_RC2_64_CBC = 'RC2-64-CBC';
    const METHOD_RC2_CBC = 'RC2-CBC';
    const METHOD_RC2_CFB = 'RC2-CFB';
    const METHOD_RC2_ECB = 'RC2-ECB';
    const METHOD_RC2_OFB = 'RC2-OFB';
    const METHOD_RC4 = 'RC4';
    const METHOD_RC4_40 = 'RC4-40';
    const METHOD_RC4_HMAC_MD5 = 'RC4-HMAC-MD5';
    const METHOD_SEED_CBC = 'SEED-CBC';
    const METHOD_SEED_CFB = 'SEED-CFB';
    const METHOD_SEED_ECB = 'SEED-ECB';
    const METHOD_SEED_OFB = 'SEED-OFB';


    /**
     * @param string $method
     *
     * @return bool
     */
    public static function isCipherMethod(string $method)
    {
        return in_array($method, openssl_get_cipher_methods());
    }
}