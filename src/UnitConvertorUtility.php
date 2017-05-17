<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt;


class UnitConvertorUtility
{
    /**
     * Convert bytes into K, M, G, T
     *
     * @param float $byteSize
     * @param int   $baseFloor
     * @param bool  $round
     * @param int   $precision
     * @param bool  $suffix
     * @param bool  $mebibyte
     *
     * @return float|string
     */
    public static function formatBytes(
        float $byteSize,
        int $baseFloor = null,
        bool $round = true,
        int $precision = 2,
        bool $suffix = false,
        bool $mebibyte = true
    )
    {
        // @link https://en.wikipedia.org/wiki/Mebibyte
        $byteBase = ($mebibyte) ? 1024 : 1000;
        $suffixes = ($mebibyte) ? ['', 'KiB', 'MiB', 'GiB', 'TiB'] : ['', 'kB', 'MB', 'GB', 'TB'];

        $base      = log($byteSize, $byteBase);
        $baseFloor = (!is_numeric($baseFloor)) ? floor($base) : $baseFloor;

        $returnValue = pow($byteBase, $base - $baseFloor);
        $returnValue = ($round) ? round($returnValue, $precision) : $returnValue;

        return ($suffix) ? "{$returnValue} {$suffixes[(int)$baseFloor]}" : $returnValue;
    }

    /**
     * Return the (string)$var size in Bytes
     *
     * strlen() returns the number of bytes rather than the number of characters in a string
     * strlen() returns NULL when executed on arrays, and an E_WARNING level error is emitted
     * @link http://php.net/manual/en/function.strlen.php
     *
     * @param string $var
     *
     * @return int
     */
    public static function varBytes(string $var)
    {
        return strlen($var);
    }

    /**
     * Return the (string)$var size in Mebibytes
     *
     * @param string $var
     *
     * @return float
     */
    public static function varMebibytes(string $var)
    {
        return self::formatBytes(self::varBytes($var), 2);
    }

    /**
     * Return the (string)$var size in Megabytes
     *
     * @param string $var
     *
     * @return float
     */
    public static function varMegabytes(string $var)
    {
        return self::formatBytes(self::varBytes($var), 2, true, 2, false, false);
    }
}