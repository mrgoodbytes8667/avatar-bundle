<?php

namespace Bytes\AvatarBundle\Tests\Enums;

use Bytes\AvatarBundle\Enums\AvatarSize;
use Bytes\Tests\Common\TestEnumTrait;
use Bytes\Tests\Common\TestSerializerTrait;
use Generator;
use PHPUnit\Framework\TestCase;

class AvatarSizeTest extends TestCase
{
    use TestSerializerTrait, TestEnumTrait;

    /**
     * @dataProvider provideLabelsValues
     * @param $label
     * @param $value
     */
    public function testEnum($label, $value)
    {
        $enum = AvatarSize::from($value);
        $this->assertEquals($value, $enum->value);
    }

    /**
     * @dataProvider provideLabelsValues
     * @param $label
     * @param $value
     */
    public function testEnumSerialization($label, $value)
    {
        $serializer = $this->createSerializer();
        $enum = AvatarSize::from($value);

        $output = $serializer->serialize($enum, 'json');

        $this->assertEquals(json_encode([
            'label' => $label,
            'value' => $value
        ]), $output);
    }

    /**
     * @return Generator
     */
    public function provideLabelsValues()
    {
        yield ['label' => 's20', 'value' => 20];
    }

    /**
     *
     */
    public function testInvalidValue()
    {
        $this->expectException(\TypeError::class);
        AvatarSize::from('abc123');
    }

    /**
     * @group legacy
     * @return void
     */
    public function testSpatieEnumMethods()
    {
        $this->assertEquals(AvatarSize::s20, AvatarSize::s20);
        $this->assertEquals(AvatarSize::s30, AvatarSize::s30);
        $this->assertEquals(AvatarSize::s80, AvatarSize::s80);
        $this->assertEquals(AvatarSize::s99, AvatarSize::s99);
        $this->assertEquals(AvatarSize::s128, AvatarSize::s128);
        $this->assertEquals(AvatarSize::s157, AvatarSize::s157);
        $this->assertEquals(AvatarSize::s253, AvatarSize::s253);
        $this->assertEquals(AvatarSize::s277, AvatarSize::s277);
        $this->assertEquals(AvatarSize::s300, AvatarSize::s300);
    }
}
