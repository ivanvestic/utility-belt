<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Object;

/**
 * Class AbstractDataValueObject
 */
abstract class AbstractDataValueObject
{
    use ObjectConstructionTrait;

    public function __construct($data)
    {
        $args = func_get_args();
        $arg2 = $args[1] ?? null;
        $arg3 = $args[2] ?? null;

        if (null !== $arg2 && null !== $arg3) {
            $this->setProperties($data, $arg2, $arg3);
        }
        elseif (null !== $arg2 && null === $arg3) {
            $this->setProperties($data, $arg2);
        }
        else {
            $this->setProperties($data);
        }
    }
}