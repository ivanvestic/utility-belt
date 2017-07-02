<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Tests\Fixture;

use IvanVestic\UtilityBelt\Object\AbstractDataValueObject;

/**
 * Class DummyBookFixture
 */
class DummyBookFixture extends AbstractDataValueObject
{
     /** @var string */
     protected $title;
    /** @var string */
     protected $author;

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