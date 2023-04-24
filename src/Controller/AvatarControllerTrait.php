<?php


namespace Bytes\AvatarBundle\Controller;


use Bytes\AvatarBundle\Entity\UserInterface;
use LogicException;
use Symfony\Component\Security\Core\Security;

/**
 * Trait AvatarControllerTrait
 * @package Bytes\AvatarBundle\Controller
 */
trait AvatarControllerTrait
{
    /**
     * @var \Symfony\Bundle\SecurityBundle\Security
     */
    private \Symfony\Bundle\SecurityBundle\Security $security;

    /**
     * @var Image
     */
    private Image $image;

    /**
     * Get a user from the Security Token Storage.
     *
     * @return UserInterface|object|null
     *
     * @throws LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    protected function getUser()
    {
        if (null === $token = $this->security->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }

    /**
     * @return \Symfony\Bundle\SecurityBundle\Security
     */
    protected function getSecurity(): \Symfony\Bundle\SecurityBundle\Security
    {
        return $this->security;
    }

    /**
     * @param \Symfony\Bundle\SecurityBundle\Security $security
     * @return $this
     */
    public function setSecurity(\Symfony\Bundle\SecurityBundle\Security $security): self
    {
        $this->security = $security;
        return $this;
    }

    /**
     * @return Image
     */
    protected function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function setImage(Image $image): self
    {
        $this->image = $image;
        return $this;
    }
}