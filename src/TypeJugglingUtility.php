<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt;


class TypeJugglingUtility
{
    /**
     * Convert any N-dimensional collection into a N-dimensional \stdClass object,
     * while preserving the same keys and the same order.
     * Optionally values can be filtered-out by key, via the $blacklist argument.
     *
     * @param object|array $collection                          A collection (array|object) var
     *
     * @param bool         $useReflectionForPublicAccessibility [optional] If true, reflection will be used for
     *                                                          handling objects with non-public properties.
     *
     * @param array        $blacklist                           [optional] List of keys to exclude (on any N level)
     *
     * @param bool         $filterByKeyExists                   [optional] When set to true key_exists will be used instead of in_array,
     *                                                          $blacklist[$k] has to be == true in order to $arr[$k] to be skipped
     *
     * @return \stdClass|null
     */
    public static function convertCollectionToObject(
        $collection,
        bool $useReflectionForPublicAccessibility = true,
        array $blacklist = [],
        bool $filterByKeyExists = false
    )
    {
        if (!($isObject = is_object($collection)) && !is_array($collection)) {
            trigger_error('$collection must be array or object, '.gettype($collection).' given');
            return null;
        }

        $object = new \stdClass();
        // if is object use reflection to handle properties/values
        // else it's an array
        $_collection = ($isObject) ? (new \ReflectionObject($collection))->getProperties() : $collection;
        foreach ($_collection as $k => $v) {
            $k = ($isObject) ? $v->getName() : $k;

            // skip $arr[$k] if $k is listed in $blacklist
            if (!empty($blacklist)
                && ((!$filterByKeyExists && in_array($k, $blacklist))
                    || ($filterByKeyExists && key_exists($k, $blacklist) && $blacklist[$k])
                )
            ) {
                continue;
            }
            // if is object, handle/prepare with reflection
            if ($isObject) {
                if (!$v->isPublic()) {
                    if ($useReflectionForPublicAccessibility) {
                        $v->setAccessible(true);
                    } else {
                        continue;
                    }
                }
                $v = $v->getValue($collection);
            }

            if (is_array($v) || is_object($v)) {
                $v = self::convertCollectionToObject($v, $useReflectionForPublicAccessibility, $blacklist, $filterByKeyExists);
            }

            $object->{$k} = $v;
        }

        return $object;
    }

    /**
     * Convert any N-dimensional collection into a N-dimensional array,
     * while preserving the same keys and the same order.
     * Optionally values can be filtered-out by key, via the $blacklist argument.
     *
     * @param object|array $collection                          A collection (array|object) var
     *
     * @param bool         $useReflectionForPublicAccessibility [optional] If true, reflection will be used for
     *                                                          handling objects with non-public properties.
     *
     * @param array        $blacklist                           [optional] List of keys to exclude (on any N level)
     *
     * @param bool         $filterByKeyExists                   [optional] When set to true key_exists will be used instead of in_array,
     *                                                          $blacklist[$k] has to be == true in order to $arr[$k] to be skipped
     *
     * @return array|null
     */
    public static function convertCollectionToArray(
        $collection,
        bool $useReflectionForPublicAccessibility = true,
        array $blacklist = [],
        bool $filterByKeyExists = false
    )
    {
        if (!($isObject = is_object($collection)) && !is_array($collection)) {
            trigger_error('$collection must be object or array, '.gettype($collection).' given');
            return null;
        }

        $array = [];
        // if is object use reflection to handle properties/values
        // else it's an array
        $_collection = ($isObject) ? (new \ReflectionObject($collection))->getProperties() : $collection;
        foreach ($_collection as $k => $v) {
            $k = ($isObject) ? $v->getName() : $k;

            // skip $obj->{$k} if $k is listed in $blacklist
            if (!empty($blacklist)
                && ((!$filterByKeyExists && in_array($k, $blacklist))
                    || ($filterByKeyExists && key_exists($k, $blacklist) && $blacklist[$k])
                )
            ) {
                continue;
            }
            // if is object, handle/prepare with reflection
            if ($isObject) {
                if (!$v->isPublic()) {
                    if ($useReflectionForPublicAccessibility) {
                        $v->setAccessible(true);
                    } else {
                        continue;
                    }
                }
                $v = $v->getValue($collection);
            }
            // if $v is a collection do a recursive call
            if (is_object($v) || is_array($v)) {
                $v = self::convertCollectionToArray($v, $useReflectionForPublicAccessibility, $blacklist, $filterByKeyExists);
            }

            $array[$k] = $v;
        }

        return $array;
    }
}