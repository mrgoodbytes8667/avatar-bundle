<?php

namespace Bytes\AvatarBundle\Tests\Avatar;

use Bytes\AvatarBundle\Avatar\Gravatar;
use Bytes\Common\Faker\TestFakerTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class GravatarTest
 * @package Bytes\AvatarBundle\Tests\Avatar
 */
class GravatarTest extends TestCase
{
    use TestFakerTrait;

    /**
     *
     */
    public function testUrl()
    {
        $email = $this->faker->email();
        $url = Gravatar::GRAVATAR_URL . md5(strtolower(trim($email)));

        $gravatar = Gravatar::url($email);
        $this->assertStringStartsWith($url, $gravatar);
        $this->assertStringContainsString('retro', $gravatar);
        $this->assertStringContainsString('g', $gravatar);
    }

    /**
     *
     */
    public function testSupportsMultipleSizes()
    {
        $this->assertTrue(Gravatar::supportsMultipleSizes());
    }

    /**
     *
     */
    public function testGet()
    {
        $email = $this->faker->email();
        $url = Gravatar::GRAVATAR_URL . md5(strtolower(trim($email)));

        $gravatar = Gravatar::get($email, atts: ['a' => 'b']);
        $this->assertStringStartsWith($url, $gravatar);
        $this->assertStringEndsNotWith('b', $gravatar);

        $gravatar = Gravatar::get($email, img: true, atts: ['a' => 'b']);
        $this->assertStringStartsWith('<img src="' . $url, $gravatar);
        $this->assertStringEndsWith('a="b" />', $gravatar);
    }
}
