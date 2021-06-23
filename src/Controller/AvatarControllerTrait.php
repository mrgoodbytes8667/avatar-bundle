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
     * @var Security
     */
    private Security $security;

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
     * @return Security
     */
    protected function getSecurity(): Security
    {
        return $this->security;
    }

    /**
     * @param Security $security
     * @return $this
     */
    public function setSecurity(Security $security): self
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