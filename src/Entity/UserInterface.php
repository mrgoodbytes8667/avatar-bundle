<?php


namespace Bytes\AvatarBundle\Entity;


/**
 * Interface UserInterface
 * @package Bytes\AvatarBundle\Entity
 */
interface UserInterface
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