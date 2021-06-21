<?php


namespace Bytes\AvatarBundle\Entity;


use Symfony\Component\Security\Core\User\UserInterface as CoreUserInterface;

/**
 * Interface UserInterface
 * @package Bytes\AvatarBundle\Entity
 */
interface UserInterface extends CoreUserInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param int $size
     * @return string
     */
    public function getGravatar(int $size = 80);

    /**
     * @return string|null
     */
    public function getAvatar(): ?string;
}