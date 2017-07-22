<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Object;

use IvanVestic\UtilityBelt\TypeJugglingUtility;
use \stdClass;

/**
 * Class AbstractValueObject
 *
 * Child classes/objects which will be extending this class are intended to be immutable.
 * The keyword being INTENDED to be immutable, read the important note below for more info...
 * Which is why all the properties/values are expected to be defined during object construction,
 * hence the usage of ObjectConstructionTrait.
 *
 * IMPORTANT: The current (2017-07-XX) state of object immutability in php, all the way up to the current
 *            php version <= 7.1.*, is that 100% object immutability guarantee goes down the drain
 *            as soon as e.g. objects or array types are introduced as object properties.
 *            That's because PHP returns objects as references, rather than as copies,
 *            which means using an object as a property value compromises the immutability of the parent object.
 *
 * Recommended: In order to help preserve immutability of the parent object properties as much as possible with a reasonable effort,
 *              e.g. when working with entities or any other object type which might need to have setters/modifiers
 *              instead of just setting the new value, implement returning a new copy of the object with the new/changed value
 *
 * @link https://en.wikipedia.org/wiki/Value_object
 * @link https://martinfowler.com/bliki/ValueObject.html
 * @link https://dzone.com/articles/practical-php-patterns/basic/practical-php-patterns-value
 * @link https://codete.com/blog/value-objects/
 * @link https://wiki.php.net/rfc/immutability
 * @link http://paul-m-jones.com/archives/6400
 */
abstract class AbstractValueObject
{
    use ObjectConstructionTrait;

    /**
     *
     */
    public function __construct()
    {
        $args = func_get_args();
        $arg1 = $args[0] ?? [];
        $arg2 = $args[1] ?? null;
        $arg3 = $args[2] ?? null;
        // unset
        $args = null;

        if (null !== $arg2 && null !== $arg3) {
            $this->setProperties($arg1, $arg2, $arg3);
        }
        elseif (null !== $arg2 && null === $arg3) {
            $this->setProperties($arg1, $arg2);
        }
        else {
            $this->setProperties($arg1);
        }
    }

    /**
     * Disallow setting dynamic properties
     *
     * @param string|int $name
     * @param mixed      $value
     */
    public function __set($name, $value)
    {
        $class = static::class;
        trigger_error("writing data to {$class}->\${$name} property is not allowed", E_USER_ERROR);
    }

    /**
     * Disallow un-setting properties
     *
     * @param string|int $name
     */
    public function __unset($name)
    {
        $class = static::class;
        trigger_error("unsetting {$class}->\${$name} property is not allowed", E_USER_ERROR);
    }

    /**
     * Iterate through all the levels of all properties of the parent object
     * and return as pure array
     *
     * @return array
     */
    public function __toArray()
    {
        return TypeJugglingUtility::convertCollectionToArray($this, true);
    }

    /**
     * Iterate through all the levels of all properties of the parent object
     * and return as pure stdClass object
     *
     * @return stdClass
     */
    public function __toObject()
    {
        return TypeJugglingUtility::convertCollectionToObject($this, true);
    }
}