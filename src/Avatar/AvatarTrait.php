<?php


namespace Bytes\AvatarBundle\Avatar;


use Bytes\AvatarBundle\Entity\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\UuidV6;

/**
 * Trait AvatarTrait
 * @package Bytes\AvatarBundle\Avatar
 *
 * @property $user
 */
trait AvatarTrait
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @param UserInterface|mixed|null $user
     * @return UuidV6|mixed|null
     */
    protected function getUserId($user)
    {
        if (!empty($user)) {
            if ($user instanceof UserInterface) {
                return $user->getId();
            }
        }
        if (!empty($this->user)) {
            return $this->user->getId();
        }
        return $user;
    }

    /**
     * @return UrlGeneratorInterface
     */
    public function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->urlGenerator;
    }

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @return $this
     */
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        return $this;
    }
}