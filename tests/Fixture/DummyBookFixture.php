<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Tests\Fixture;


use IvanVestic\UtilityBelt\Object\AbstractDataValueObject;
use IvanVestic\UtilityBelt\Object\ObjectConstructionTrait;

/**
 * Class DummyBookFixture
 */
class DummyBookFixture extends AbstractDataValueObject
{
     use ObjectConstructionTrait;

     /** @var string */
     protected $title;
    /** @var string */
     protected $author;

    public function __construct()
    {
        parent::__construct(...func_get_args());
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function author()
    {
        return $this->author;
    }
}