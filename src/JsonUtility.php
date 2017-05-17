<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt;

/**
 * Class JsonUtility
 */
class JsonUtility
{
    /**
     * @param string $jsonString
     * @param bool   $returnArray
     *
     * @return bool|null|mixed
     */
    public static function isJson(string $jsonString, bool $returnArray = null)
    {
        /** @var mixed $jsonData */
        $jsonData = json_decode($jsonString, $returnArray ?? false);

        if (json_last_error() == JSON_ERROR_NONE) {
            // given $jsonString is valid json format
            return (is_bool($returnArray)) ? $jsonData : true;
        }

        // given $jsonString is not a valid json format
        return (is_bool($returnArray)) ? null : false;
    }
}