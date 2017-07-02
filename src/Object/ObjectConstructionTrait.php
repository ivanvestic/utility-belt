<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Object;

use \stdClass;

/**
 * Class ObjectConstructionTrait
 */
trait ObjectConstructionTrait
{
    /**
     * Enforce __construct method definition when using the ObjectConstructionTrait
     */
    abstract public function __construct();

    /**
     * Intended to be used in a class constructor,
     * making it convenient to set all necessary properties at once.
     *
     * @param array|stdClass $data
     *
     * @param array        $remap provides a convenient optional ability to "remap" the object property value,
     *                     either via callable (and $data value), or directly from $remap if $data value is not defined
     *
     * @param array        $callableRemapByRef
     *
     * @param int          $reflectionPropertiesFilter
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    private function setProperties($data, array $remap = [], array $callableRemapByRef = [], int $reflectionPropertiesFilter = null)
    {
        // validate $data
        $isArray = is_array($data);
        $isObject = (is_object($data) && ($data instanceof stdClass));
        if (!$isArray && !$isObject) {
            throw new \InvalidArgumentException('invalid argument: unsupported $data type: '.gettype($data));
        }

        // get $this properties
        $reflectionObject = new \ReflectionObject($this);
        $properties = ((null === $reflectionPropertiesFilter) ? $reflectionObject->getProperties() : $reflectionObject->getProperties($reflectionPropertiesFilter));
        if (null == $properties) {
            // no properties found for provided $reflectionPropertiesFilter
            return;
        }

        foreach ($properties as $reflectionProperty) {
            /** @var \ReflectionProperty $reflectionProperty */
            $propertyName = $reflectionProperty->getName();

            // is $propertyName defined in $data
            $hasData = (($isArray && key_exists($propertyName, $data)) || ($isObject && property_exists($data, $propertyName)));
            // is $propertyName defined in $remap
            $hasRemap = key_exists($propertyName, $remap);

            if(($hasRemap && is_callable($remap[$propertyName])) && $hasData) {
                // 1. callable remap
                if (key_exists($propertyName, $callableRemapByRef) && false === $callableRemapByRef[$propertyName]) {
                    $this->{$propertyName} = $remap[$propertyName]((($isArray) ? $data[$propertyName] : $data->{$propertyName}));
                }
                else {
                    $remap[$propertyName]((($isArray) ? $data[$propertyName] : $data->{$propertyName}));
                }
            }
            elseif ($hasData) {
                // 2. set property value via $data value
                $this->{$propertyName} = (($isArray) ? $data[$propertyName] : $data->{$propertyName});
            }
            elseif($hasRemap) {
                // 3. set property value via $remap value ($data value was not defined)
                $this->{$propertyName} = $remap[$propertyName];
            }
        }
    }
}