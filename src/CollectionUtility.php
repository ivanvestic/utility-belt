<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt;


class CollectionUtility
{
    /**
     * Check if $var is either an array or object,
     * and optionally return the $var type.
     *
     * @param array|object $var
     * @param bool         $returnType
     *
     * @return bool|string
     */
    public static function isCollection($var, bool $returnType = false)
    {
        if (!is_array($var) && !is_object($var)) {
            return false;
        }

        return (!$returnType) ? true : gettype($var);
    }

    /**
     * Overwrite $collection1 values with $collection2 values by matched keys,
     * while preserving the original order of $collection1.
     * Multidimensional collections are handled with recursion,
     * which is implemented as an recursive anonymous function.
     * Both "original" top-level collections have to be of the same type.
     *
     * Optionally when $hashDiffAlgo is set, $collection1 values will not be overwritten by $collection2 values,
     * instead $collection2 is expected to contain hash values which will be compared to the related $collection1 values.
     * This is useful and a convenient way to compare/diff values in a collection, all in one step.
     *
     * @param array|object $collection1      Original collection
     * @param array|object $collection2      Replacement collection $collection2[$key] = $va
     * @param bool         $preserveDiff     When set to true preserve $collection1[$key] values,
     *                                       where (!array_key_exists($key, $collection2))
     * @param bool         $returnAsArray    When set to true return $result as 100% array, else return 100% \stdClass obj
     * @param string|null  $hashDiffAlgo     @link http://php.net/manual/en/function.hash-algos.php
     * @param bool         $preserveHashDiff
     * @param bool         $setDiffFlag
     * @param string       $hashKeySuffix
     * @param string       $diffKeySuffix
     *
     * @return array|\stdClass|null
     */
    public static function collectionOverwrite(
        $collection1,
        $collection2,
        bool $preserveDiff,
        bool $returnAsArray = true,
        string $hashDiffAlgo = '',
        bool $preserveHashDiff = false,
        bool $setDiffFlag = true,
        string $hashKeySuffix = '_hash',
        string $diffKeySuffix = '_diff'
    )
    {

        $typeCollection1 = self::isCollection($collection1, true);
        $typeCollection2 = self::isCollection($collection2, true);

        // supported types: array|object
        if (!$typeCollection1 || !$typeCollection2) {
            trigger_error("invalid argument type \$collection1 and/or \$collection2", E_USER_ERROR);
            return null;
        }

        // recursive anonymous function definition
        $recursiveCollectionOverwriteAnonymousFunction = function(
            array $collection1,
            array $collection2,
            array $originalCollection2,
            bool $preserveDiff,
            bool $returnAsArray,
            string $hashDiffAlgo,
            bool $preserveHashDiff,
            bool $setDiffFlag,
            string $hashKeySuffix,
            string $diffKeySuffix
        ) use (&$recursiveCollectionOverwriteAnonymousFunction) {

            $result = [];

            // iterate through $collection1, and find intersects with $collection2 by key
            foreach ($collection1 as $key => $val) {

                if (!array_key_exists($key, $collection2)) {
                    if (!$preserveDiff) {
                        continue;
                    }
                    $collection2[$key] = $val;
                }

                if (is_array($val) && is_array($collection2[$key])) {
                    if (empty($val) && !empty($collection2[$key])) {
                        if ('' != $hashDiffAlgo && array_key_exists($key, $originalCollection2)) {
                            $dataHash = hash($hashDiffAlgo, serialize($val));

                            $result[$key] = ($dataHash == $collection2[$key] && !$preserveHashDiff) ? null : $val;
                            $result["{$key}{$hashKeySuffix}"] = $dataHash;
                            if ($setDiffFlag) {
                                $result["{$key}{$diffKeySuffix}"] = ($dataHash == $collection2[$key]);
                            }
                        }
                        else {
                            $result[$key] = $collection2[$key];
                        }
                    }
                    else {
                        // recursion
                        $result[$key] = $recursiveCollectionOverwriteAnonymousFunction(
                            $val,
                            $collection2[$key],
                            $originalCollection2[$key] ?? $originalCollection2,
                            $preserveDiff,
                            $returnAsArray,
                            $hashDiffAlgo,
                            $preserveHashDiff,
                            $setDiffFlag,
                            $hashKeySuffix,
                            $diffKeySuffix
                        );
                    }
                }
                else {
                    if ('' != $hashDiffAlgo && array_key_exists($key, $originalCollection2)) {
                        $dataHash = hash($hashDiffAlgo, serialize($val));

                        $result[$key] = ($dataHash == $collection2[$key] && !$preserveHashDiff) ? null : $val;
                        $result["{$key}{$hashKeySuffix}"] = $dataHash;
                        if ($setDiffFlag) {
                            $result["{$key}{$diffKeySuffix}"] = ($dataHash == $collection2[$key]) ? false : true;
                        }
                    }
                    else {
                        $result[$key] = $collection2[$key];
                    }
                }
            }

            return $result;
        };
        // END $recursiveCollectionOverwriteAnonymousFunction definition

        // call the recursive anonymous function
        $result = $recursiveCollectionOverwriteAnonymousFunction(
            ($collection1 = TypeJugglingUtility::convertCollectionToArray($collection1)),
            ($collection2 = TypeJugglingUtility::convertCollectionToArray($collection2)),
            $collection2,
            $preserveDiff,
            $returnAsArray,
            $hashDiffAlgo,
            $preserveHashDiff,
            $setDiffFlag,
            $hashKeySuffix,
            $diffKeySuffix
        );
        if (!is_array($result)) {
            return null;
        }

        return (!$returnAsArray) ? TypeJugglingUtility::convertCollectionToObject($result) : $result;
    }
}