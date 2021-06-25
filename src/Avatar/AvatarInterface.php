<?php


namespace Bytes\AvatarBundle\Avatar;


use Bytes\AvatarBundle\Enums\AvatarSize;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Interface AvatarInterface
 * @package Bytes\AvatarBundle\Avatar
 */
interface AvatarInterface
{
    /**
     * Does this generator support multiple sizes?
     * @return bool
     */
    public static function supportsMultipleSizes(): bool;

    /**
     * Generates the avatar URL
     * @param null $user
     * @param AvatarSize|null $size
     * @return string
     */
    public function generate($user = null, ?AvatarSize $size = null);

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @return $this
     */
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator);
}