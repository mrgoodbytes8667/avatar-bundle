<?php

namespace Bytes\AvatarBundle\Tests\Event;

use Bytes\AvatarBundle\Event\ResolveCacheEvent;
use Bytes\Common\Faker\TestFakerTrait;
use DateTimeImmutable;
use Generator;
use Liip\ImagineBundle\Async\Commands;
use PHPUnit\Framework\TestCase;

/**
 * Class ResolveCacheEventTest
 * @package Bytes\AvatarBundle\Tests\Event
 */
class ResolveCacheEventTest extends TestCase
{
    use TestFakerTrait;

    /**
     * @dataProvider provideEventArgs
     * @param $path
     * @param $filters
     * @param $force
     */
    public function testEvent($path, $filters, $force)
    {
        $event = ResolveCacheEvent::new($path, $filters, $force);

        $this->assertInstanceOf(ResolveCacheEvent::class, $event);
        $this->assertEquals($path, $event->getPath());
        $this->assertEquals($filters, $event->getFilters());
        $this->assertEquals($force, $event->isForce());
    }

    /**
     * @return Generator
     */
    public function provideEventArgs()
    {
        $this->setupFaker();
        $path = $this->faker->word() . '.' . $this->faker->fileExtension();
        $filters = $this->faker->words(3);

        yield ['path' => $path, 'filters' => $filters, 'force' => true];
        yield ['path' => $path, 'filters' => $filters, 'force' => false];
        yield ['path' => $path, 'filters' => [], 'force' => true];
        yield ['path' => $path, 'filters' => [], 'force' => false];
    }

    /**
     * @return Generator
     */
    public function provideSingleEventArgs()
    {
        $this->setupFaker();
        $path = $this->faker->word() . '.' . $this->faker->fileExtension();
        $filters = $this->faker->optional(default: [])->words(3);
        $force = $this->faker->boolean();

        yield ['path' => $path, 'filters' => $filters, 'force' => $force];
    }

    /**
     * @dataProvider provideSingleEventArgs
     * @param $path
     * @param $filters
     * @param $force
     */
    public function testGetSetResults($path, $filters, $force)
    {
        $event = ResolveCacheEvent::new($path, $filters, $force);

        $this->assertCount(0, $event->getResults());
        $this->assertInstanceOf(ResolveCacheEvent::class, $event->setResults([1, 2, 3]));
        $this->assertCount(3, $event->getResults());
    }

    /**
     * @dataProvider provideSingleEventArgs
     * @param $path
     * @param $filters
     * @param $force
     */
    public function testGetSetPath($path, $filters, $force)
    {
        $event = ResolveCacheEvent::new($path, $filters, $force);
        $newPath = $this->faker->word() . '.' . $this->faker->fileExtension();

        $this->assertEquals($path, $event->getPath());
        $this->assertInstanceOf(ResolveCacheEvent::class, $event->setPath($newPath));
        $this->assertEquals($newPath, $event->getPath());
    }

    /**
     * @dataProvider provideSingleEventArgs
     * @param $path
     * @param $filters
     * @param $force
     */
    public function testIsForce($path, $filters, $force)
    {
        $event = ResolveCacheEvent::new($path, $filters, false);

        $this->assertFalse($event->isForce());
        $this->assertInstanceOf(ResolveCacheEvent::class, $event->setForce(true));
        $this->assertTrue($event->isForce());
    }

    /**
     * @dataProvider provideSingleEventArgs
     * @param $path
     * @param $filters
     * @param $force
     */
    public function testGetSetFilters($path, $filters, $force)
    {
        $event = ResolveCacheEvent::new($path, $filters, $force);
        $newFilters = $this->faker->words(4);

        $this->assertEquals($filters, $event->getFilters());
        $this->assertInstanceOf(ResolveCacheEvent::class, $event->setFilters($newFilters));
        $this->assertEquals($newFilters, $event->getFilters());
    }

    /**
     * @dataProvider provideSingleEventArgs
     * @param $path
     * @param $filters
     * @param $force
     */
    public function testGetSetCommand($path, $filters, $force)
    {
        $event = ResolveCacheEvent::new($path, $filters, $force);
        $newCommand = $this->faker->word();

        $this->assertEquals(Commands::RESOLVE_CACHE, $event->getCommand());
        $this->assertInstanceOf(ResolveCacheEvent::class, $event->setCommand($newCommand));
        $this->assertEquals($newCommand, $event->getCommand());
    }

    /**
     * @dataProvider provideSingleEventArgs
     * @param $path
     * @param $filters
     * @param $force
     */
    public function testGetSetCreatedAt($path, $filters, $force)
    {
        $event = ResolveCacheEvent::new($path, $filters, $force);
        $createdAt = $event->getCreatedAt();
        $newCreatedAt = DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween(endDate: '-5 years'));

        $this->assertEquals($createdAt, $event->getCreatedAt());
        $this->assertInstanceOf(ResolveCacheEvent::class, $event->setCreatedAt($newCreatedAt));
        $this->assertEquals($newCreatedAt, $event->getCreatedAt());
    }
}
