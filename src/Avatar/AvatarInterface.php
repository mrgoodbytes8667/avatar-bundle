<?php


namespace Bytes\AvatarBundle\Avatar;


use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\AvatarBundle\Enums\AvatarSize;

/**
 * Interface AvatarInterface
 * @package Bytes\AvatarBundle\Avatar
 */
interface AvatarInterface
{
    /**
     * @param UserInterface|null $user
     * @param AvatarSize|null $size
     * @return string
     */
    public function generate(?UserInterface $user = null, ?AvatarSize $size = null);
}