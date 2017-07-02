<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Hash;


/**
 * Class HashUtility
 */
class HashUtility
{
    // these hashing algorithms were extracted as constants for convenience
    // from hash_algos() php v7.0.15
    // @link http://php.net/manual/en/function.hash-algos.php
    const ALGO_MD2 = 'md2';
    const ALGO_MD4 = 'md4';
    const ALGO_MD5 = 'md5';
    const ALGO_SHA1 = 'sha1';
    const ALGO_SHA224 = 'sha224';
    const ALGO_SHA256 = 'sha256';
    const ALGO_SHA384 = 'sha384';
    const ALGO_SHA512 = 'sha512';
    const ALGO_RIPEMD128 = 'ripemd128';
    const ALGO_RIPEMD160 = 'ripemd160';
    const ALGO_RIPEMD256 = 'ripemd256';
    const ALGO_RIPEMD320 = 'ripemd320';
    const ALGO_WHIRLPOOL = 'whirlpool';
    const ALGO_TIGER128_3 = 'tiger128,3';
    const ALGO_TIGER160_3 = 'tiger160,3';
    const ALGO_TIGER192_3 = 'tiger192,3';
    const ALGO_TIGER128_4 = 'tiger128,4';
    const ALGO_TIGER160_4 = 'tiger160,4';
    const ALGO_TIGER192_4 = 'tiger192,4';
    const ALGO_SNEFRU = 'snefru';
    const ALGO_SNEFRU256 = 'snefru256';
    const ALGO_GOST = 'gost';
    const ALGO_GOST_CRYPTO = 'gost-crypto';
    const ALGO_ADLER32 = 'adler32';
    const ALGO_CRC32 = 'crc32';
    const ALGO_CRC32B = 'crc32b';
    const ALGO_FNV132 = 'fnv132';
    const ALGO_FNV1A32 = 'fnv1a32';
    const ALGO_FNV164 = 'fnv164';
    const ALGO_FNV1A64 = 'fnv1a64';
    const ALGO_JOAAT = 'joaat';
    const ALGO_HAVAL128_3 = 'haval128,3';
    const ALGO_HAVAL160_3 = 'haval160,3';
    const ALGO_HAVAL192_3 = 'haval192,3';
    const ALGO_HAVAL224_3 = 'haval224,3';
    const ALGO_HAVAL256_3 = 'haval256,3';
    const ALGO_HAVAL128_4 = 'haval128,4';
    const ALGO_HAVAL160_4 = 'haval160,4';
    const ALGO_HAVAL192_4 = 'haval192,4';
    const ALGO_HAVAL224_4 = 'haval224,4';
    const ALGO_HAVAL256_4 = 'haval256,4';
    const ALGO_HAVAL128_5 = 'haval128,5';
    const ALGO_HAVAL160_5 = 'haval160,5';
    const ALGO_HAVAL192_5 = 'haval192,5';
    const ALGO_HAVAL224_5 = 'haval224,5';
    const ALGO_HAVAL256_5 = 'haval256,5';


    /**
     * @param string $algo
     *
     * @return bool
     */
    public static function isHashAlgo(string $algo)
    {
        return in_array($algo, hash_algos());
    }
}