<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt;


class ArrayUtility
{
    /**
     * Check if array is associative or not
     *
     * @param array $array
     *
     * @return bool
     */
    final public static function isAssoc(array $array)
    {
        return array_keys($array) !== range(0, count($array) -1);
    }

    /**
     * Check if array is sequential or not
     *
     * @param array $array
     *
     * @return bool
     */
    final public static function isSequential(array $array)
    {
        return [] === $array || !self::isAssoc($array);
    }
}