<?php


namespace Bytes\AvatarBundle\Enums;


use Bytes\EnumSerializerBundle\Enums\BackedEnumInterface;
use Bytes\EnumSerializerBundle\Enums\BackedEnumTrait;
use JetBrains\PhpStorm\Deprecated;

enum AvatarSize: int implements BackedEnumInterface
{
    use BackedEnumTrait;

    case s20 = 20;
    case s30 = 30;
    case s80 = 80;
    case s99 = 99;
    case s128 = 128;
    case s157 = 157;
    case s253 = 253;
    case s277 = 277;
    case s300 = 300;

    #[Deprecated(reason: 'since 0.7.0, use "s20" instead.', replacement: '%class%::s20')]
    public static function s20(): AvatarSize
    {
        trigger_deprecation('mrgoodbytes8667/avatar-bundle', '0.7.0', 'Using "%s" is deprecated, use "%s" instead.', __METHOD__, 's20');
        return AvatarSize::s20;
    }

    #[Deprecated(reason: 'since 0.7.0, use "s30" instead.', replacement: '%class%::s30')]
    public static function s30(): AvatarSize
    {
        trigger_deprecation('mrgoodbytes8667/avatar-bundle', '0.7.0', 'Using "%s" is deprecated, use "%s" instead.', __METHOD__, 's30');
        return AvatarSize::s30;
    }

    #[Deprecated(reason: 'since 0.7.0, use "s80" instead.', replacement: '%class%::s80')]
    public static function s80(): AvatarSize
    {
        trigger_deprecation('mrgoodbytes8667/avatar-bundle', '0.7.0', 'Using "%s" is deprecated, use "%s" instead.', __METHOD__, 's80');
        return AvatarSize::s80;
    }

    #[Deprecated(reason: 'since 0.7.0, use "s99" instead.', replacement: '%class%::s99')]
    public static function s99(): AvatarSize
    {
        trigger_deprecation('mrgoodbytes8667/avatar-bundle', '0.7.0', 'Using "%s" is deprecated, use "%s" instead.', __METHOD__, 's99');
        return AvatarSize::s99;
    }

    #[Deprecated(reason: 'since 0.7.0, use "s128" instead.', replacement: '%class%::s128')]
    public static function s128(): AvatarSize
    {
        trigger_deprecation('mrgoodbytes8667/avatar-bundle', '0.7.0', 'Using "%s" is deprecated, use "%s" instead.', __METHOD__, 's128');
        return AvatarSize::s128;
    }

    #[Deprecated(reason: 'since 0.7.0, use "s157" instead.', replacement: '%class%::s157')]
    public static function s157(): AvatarSize
    {
        trigger_deprecation('mrgoodbytes8667/avatar-bundle', '0.7.0', 'Using "%s" is deprecated, use "%s" instead.', __METHOD__, 's157');
        return AvatarSize::s157;
    }

    #[Deprecated(reason: 'since 0.7.0, use "s253" instead.', replacement: '%class%::s253')]
    public static function s253(): AvatarSize
    {
        trigger_deprecation('mrgoodbytes8667/avatar-bundle', '0.7.0', 'Using "%s" is deprecated, use "%s" instead.', __METHOD__, 's253');
        return AvatarSize::s253;
    }

    #[Deprecated(reason: 'since 0.7.0, use "s277" instead.', replacement: '%class%::s277')]
    public static function s277(): AvatarSize
    {
        trigger_deprecation('mrgoodbytes8667/avatar-bundle', '0.7.0', 'Using "%s" is deprecated, use "%s" instead.', __METHOD__, 's277');
        return AvatarSize::s277;
    }

    #[Deprecated(reason: 'since 0.7.0, use "s300" instead.', replacement: '%class%::s300')]
    public static function s300(): AvatarSize
    {
        trigger_deprecation('mrgoodbytes8667/avatar-bundle', '0.7.0', 'Using "%s" is deprecated, use "%s" instead.', __METHOD__, 's300');
        return AvatarSize::s300;
    }
}