<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Traits;


trait ObjectFactoryTrait
{
    /**
     * Intended to be used in a class constructor,
     * making it convenient to set all necessary properties at once.
     *
     * @param array|object $data
     * @param array        $mapKeyProperty provides a convenient optional ability to "re-map" $data
     *                     when setting object properties
     * @param int          $reflectionPropertyFilter
     *
     * @return void
     */
    protected function setProperties($data, array $mapKeyProperty, int $reflectionPropertyFilter = null)
    {
        $reflectionObject = new \ReflectionObject($this);
        if (null == ($properties = $reflectionObject->getProperties($reflectionPropertyFilter))) {
            // no properties found for provided $reflectionPropertyFilter
            return;
        }

        if (is_object($data)) {
            foreach ($properties as $reflectionProperty) {
                /** @var \ReflectionProperty $reflectionProperty */
                $k = $reflectionProperty->getName();

                if (!property_exists($data, $k)) {
                    if (!isset($mapKeyProperty[$k])) {
                        continue;
                    }
                    $k = $mapKeyProperty[$k];
                }
                $this->{$k} = $data->{$k};
            }
        }
        elseif(is_array($data)) {
            foreach ($properties as $reflectionProperty) {
                /** @var \ReflectionProperty $reflectionProperty */
                $k = $reflectionProperty->getName();

                if (!key_exists($k, $data)) {
                    if (!isset($mapKeyProperty[$k])) {
                        continue;
                    }
                    $k = $mapKeyProperty[$k];
                }
                $this->{$k} = $data[$k];
            }
        }
        else {
            trigger_error('invalid constructor argument: unsupported $data type', E_USER_ERROR);
        }
    }
}