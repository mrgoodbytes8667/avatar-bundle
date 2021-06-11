<?php


namespace Bytes\AvatarBundle\Avatar;


use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\AvatarBundle\Enums\AvatarSize;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\UuidV6;

/**
 * Class Avatars
 * @package Bytes\AvatarBundle\Avatar
 */
class Avatars
{
    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @var UserInterface
     */
    private UserInterface $user;

    /**
     * Avatars constructor.
     * @param Security $security
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Security $security, UrlGeneratorInterface $urlGenerator)
    {

        // grab the user, do a quick sanity check that one exists
        /** @var UserInterface $user */
        $user = $security->getUser();
        if (!empty($user)) {
            $this->user = $user;
        }
        $this->urlGenerator = $urlGenerator;

    }

    /**
     * @param null $user
     * @return string[]
     */
    public function all($user = null)
    {
        $return = [
            $this->multiAvatar($user)
        ];
        foreach (AvatarSize::toValues() as $size) {
            $return[] = $this->gravatar($user, AvatarSize::from($size));
        }
        return $return;
    }

    /**
     * @param UserInterface|string|null $user
     * @return string
     */
    public function multiAvatar($user = null)
    {
        return $this->urlGenerator->generate('bytes_avatarbundle_multiavatar', ['id' => $this->getUserId($user)]);
    }

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
     * @param UserInterface|null $user
     * @param AvatarSize|null $size
     * @return string
     */
    public function gravatar(?UserInterface $user = null, ?AvatarSize $size = null)
    {
        $size = $size ?? AvatarSize::s80();
        return $this->urlGenerator->generate('bytes_avatarbundle_gravatar', ['id' => $this->getUserId($user), 'size' => $size->value]);
    }
}