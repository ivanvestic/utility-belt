<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Object;

use \InvalidArgumentException;
use \ReflectionClass;
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
     * @param array|stdClass   $data                       Collection of values which will be applied to the properties
     *                                                     of the object being instantiated, which value to which property
     *                                                     is mapped by the $data keys.
     *
     * @param callable[]|array $remap                      [optional] Provides a convenient ability to "remap" (set)
     *                                                     any object property value, either via callable (and $data value),
     *                                                     or directly from $remap if $data (key)value is not defined.
     *
     * @param bool[]|array     $callableRemapByRef         [optional] Array of boolean flags, each related to a single object property.
     *                                                     If defined and is false, that particular property value
     *                                                     being set via callable will not be set via reference,
     *                                                     callable will return the value,
     *                                                     else if not defined or is true, that particular property value
     *                                                     being set via callable will be set via reference,
     *                                                     callable will not return the value.
     *
     * @param int|null         $reflectionPropertiesFilter [optional] For filtering desired property types,
     *                                                     e.g. IS_STATIC|IS_PUBLIC|IS_PROTECTED|IS_PRIVATE
     *                                                     @link http://php.net/manual/en/reflectionclass.getproperties.php
     *                                                     @link http://php.net/manual/en/class.reflectionproperty.php#reflectionproperty.constants.modifiers
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    private function setProperties($data, array $remap = [], array $callableRemapByRef = [], int $reflectionPropertiesFilter = null)
    {
        // validate $data
        $isArray = is_array($data);
        $isObject = (is_object($data) && ($data instanceof stdClass));
        if (!$isArray && !$isObject) {
            throw new InvalidArgumentException('invalid argument: unsupported $data type: '.gettype($data));
        }

        // get $this properties
        $reflectionClass = new ReflectionClass(static::class);
        $properties = ((null === $reflectionPropertiesFilter) ? $reflectionClass->getProperties() : $reflectionClass->getProperties($reflectionPropertiesFilter));
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

            if (($hasRemap && is_callable($remap[$propertyName])) && $hasData) {
                // 1. callable remap
                $dataValue = (($isArray) ? $data[$propertyName] : $data->{$propertyName});
                if (key_exists($propertyName, $callableRemapByRef) && false === $callableRemapByRef[$propertyName]) {
                    $this->{$propertyName} = $remap[$propertyName]($dataValue);
                } else {
                    $remap[$propertyName]($dataValue);
                    $this->{$propertyName} = $dataValue;
                }
                unset($dataValue);

            } elseif ($hasData) {
                // 2. set property value via $data value
                $this->{$propertyName} = (($isArray) ? $data[$propertyName] : $data->{$propertyName});

            } elseif($hasRemap) {
                // 3. set property value via $remap value ($data value was not defined)
                $this->{$propertyName} = $remap[$propertyName];
            }
        }
    }
}