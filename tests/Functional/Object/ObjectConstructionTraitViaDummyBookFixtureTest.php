<?php
/**
 * (c) Ivan Veštić
 * http://ivanvestic.com
 */

namespace IvanVestic\UtilityBelt\Tests\Functional\Object;

use IvanVestic\UtilityBelt\Tests\Fixture\DummyBookFixture;
use PHPUnit\Framework\TestCase;

/**
 * Class ObjectConstructionTraitViaDummyBookFixtureTest
 */
class ObjectConstructionTraitViaDummyBookFixtureTest extends TestCase
{

    /**
     * @test
     */
    public function setPropertiesOnBookFixture()
    {
        $title = 'A book about programming';
        $author = 'John Doe';

        // test BookFixture default data
        $blankBook = new DummyBookFixture([]);
        $this->assertTrue(($blankBook->title() === null));
        $this->assertTrue(($blankBook->author() === null));

        // test ObjectConstructionTrait->setProperties
        // 1. set some properties
        // 2. test if properties have been properly set
        $book = new DummyBookFixture([
            'title' => $title,
            'author' => $author
        ]);

        $this->assertTrue(($book->title() === $title));
        $this->assertTrue(($book->author() === $author));

        // test ObjectConstructionTrait->setProperties
        // 1. set some properties
        // 2. set some properties directly via remap
        // 3. test if properties have been properly set
        $book = new DummyBookFixture(
            ['title' => $title],
            ['author' => $author]
        );
        $this->assertTrue(($book->title() === $title));
        $this->assertTrue(($book->author() === $author));

        // test ObjectConstructionTrait->setProperties
        // 1. set some properties
        // 2. set some properties via remap callback (not by reference)
        // 3. test if properties have been properly set
        $book = new DummyBookFixture(
            ['title' => $title, 'author' => $author],
            ['author' => function($v) { return mb_strtoupper($v); }],
            ['author' => false]
        );
        $this->assertTrue(($book->title() === $title));
        $this->assertTrue(($book->author() === mb_strtoupper($author)));

        // test ObjectConstructionTrait->setProperties
        // 1. set some properties
        // 2. set some properties via remap callback (by reference)
        // 3. test if properties have been properly set
        $book = new DummyBookFixture(
            ['title' => $title, 'author' => $author],
            ['author' => function(&$v) { $v = mb_strtoupper($v); }],
            ['author' => true]
        );
        $this->assertTrue(($book->title() === $title));
        $this->assertTrue(($book->author() === mb_strtoupper($author)));

        // test ObjectConstructionTrait->setProperties
        // 1. set some properties
        // 2. set some properties via remap callback
        //    (by reference, particular $callableRemapByRef property/key being undefined is expected to be the same as being set to true)
        // 3. test if properties have been properly set
        $book = new DummyBookFixture(
            ['title' => $title, 'author' => $author],
            ['author' => function(&$v) { $v = mb_strtoupper($v); }]
        );
        $this->assertTrue(($book->title() === $title));
        $this->assertTrue(($book->author() === mb_strtoupper($author)));
    }
}