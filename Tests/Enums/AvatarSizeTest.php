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
        $this->assertEquals($label, $enum->label);
        $this->assertEquals($value, $enum->value);

        $enum = AvatarSize::from($label);
        $this->assertEquals($label, $enum->label);
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
        $this->expectException(\BadMethodCallException::class);
        AvatarSize::from('abc123');
    }

    /**
     *
     */
    public function testCoverage()
    {
        $this->coverEnum(AvatarSize::class);
    }
}
