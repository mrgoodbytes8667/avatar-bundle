<?php


namespace Bytes\AvatarBundle\Avatar;


use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\AvatarBundle\Enums\AvatarSize;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Interface AvatarInterface
 * @package Bytes\AvatarBundle\Avatar
 */
interface AvatarInterface
{
    /**
     * Generates the avatar URL
     * @param UserInterface|null $user
     * @param AvatarSize|null $size
     * @return string
     */
    public function generate(?UserInterface $user = null, ?AvatarSize $size = null);

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @return $this
     */
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator);

    /**
     * Does this generator support multiple sizes?
     * @return bool
     */
    public static function supportsMultipleSizes(): bool;
}