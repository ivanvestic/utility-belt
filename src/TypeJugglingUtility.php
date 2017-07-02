<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt;


class TypeJugglingUtility
{
    /**
     * Convert any N-dimensional array into a N-dimensional \stdClass object,
     * while preserving the same keys and the same order.
     * Optionally values can be filtered-out by key, via the $blacklist argument.
     *
     * @param array|object $arr
     * @param array        $blacklist              List of keys to exclude (on any N level)
     * @param bool         $filterByArrayKeyExists When set to true array_key_exists will be used instead of in_array,
     *                                             $blacklist[$k] has to be == true in order to $arr[$k] to be skipped
     *
     * @return \stdClass|null
     */
    public static function convertArrayToObject($arr, array $blacklist = [], bool $filterByArrayKeyExists = false)
    {
        if (!is_object($arr) && !is_array($arr)) {
            trigger_error('$obj must be array or object, '.gettype($arr).' given');
            return null;
        }

        $object = new \stdClass();
        foreach ($arr as $k => $v) {
            // skip $arr[$k] if $k is listed in $blacklist
            if (!empty($blacklist)
                && ((!$filterByArrayKeyExists && in_array($k, $blacklist))
                    || ($filterByArrayKeyExists && array_key_exists($k, $blacklist) && $blacklist[$k])
                )
            ) {
                continue;
            }

            if (is_array($v) || is_object($v)) {
                $v = self::convertArrayToObject($v, $blacklist, $filterByArrayKeyExists);
            }

            $object->{$k} = $v;
        }

        return $object;
    }

    /**
     * Convert any N-dimensional object into a N-dimensional array,
     * while preserving the same keys and the same order.
     * Optionally values can be filtered-out by key, via the $blacklist argument.
     *
     * @param object|array $obj
     * @param array        $blacklist              List of keys to exclude (on any N level)
     * @param bool         $filterByArrayKeyExists When set to true array_key_exists will be used instead of in_array,
     *                                             $blacklist[$k] has to be == true in order to $arr[$k] to be skipped
     *
     * @return array|null
     */
    public static function convertObjectToArray($obj, array $blacklist = [], bool $filterByArrayKeyExists = false)
    {
        if (!is_object($obj) && !is_array($obj)) {
            trigger_error('$obj must be object or array, '.gettype($obj).' given');
            return null;
        }

        $array = [];
        foreach ($obj as $k => $v) {
            // skip $obj->{$k} if $k is listed in $blacklist
            if (!empty($blacklist)
                && ((!$filterByArrayKeyExists && in_array($k, $blacklist))
                    || ($filterByArrayKeyExists && array_key_exists($k, $blacklist) && $blacklist[$k])
                )
            ) {
                continue;
            }

            if (is_object($v) || is_array($v)) {
                $v = self::convertObjectToArray($v, $blacklist, $filterByArrayKeyExists);
            }

            $array[$k] = $v;
        }

        return $array;
    }
}