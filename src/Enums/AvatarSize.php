<?php


namespace Bytes\AvatarBundle\Enums;


use Bytes\EnumSerializerBundle\Enums\Enum;

/**
 * Class AvatarSize
 * @package Bytes\AvatarBundle\Enums
 *
 * @method static self s20()
 * @method static self s30()
 * @method static self s80()
 * @method static self s99()
 * @method static self s128()
 * @method static self s157()
 * @method static self s253()
 * @method static self s277()
 * @method static self s300()
 */
class AvatarSize extends Enum
{
    /**
     * @return int[]
     */
    protected static function values(): array
    {
        return [
            's20' => 20,
            's30' => 30,
            's80' => 80,
            's99' => 99,
            's128' => 128,
            's157' => 157,
            's253' => 253,
            's277' => 277,
            's300' => 300,
        ];
    }
}