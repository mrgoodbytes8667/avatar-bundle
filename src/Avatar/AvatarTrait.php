<?php


namespace Bytes\AvatarBundle\Avatar;


use Bytes\AvatarBundle\Entity\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Ulid;
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
     * @return string
     */
    protected function getUserId($user)
    {
        if (!empty($user)) {
            if ($user instanceof UserInterface) {
                return $this->normalizeUserId($user->getId());
            }
        }
        if (!empty($this->user)) {
            return $this->normalizeUserId($this->user->getId());
        }
        return $this->normalizeUserId($user);
    }

    /**
     * @param $id
     * @return string
     */
    protected function normalizeUserId($id)
    {
        if($id instanceof AbstractUid)
        {
            return $id->toBase32();
        }
        return $id;
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