<?php


namespace Bytes\AvatarBundle\Avatar;


use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\AvatarBundle\Enums\AvatarSize;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Multiavatar implements AvatarInterface
{
    use AvatarTrait;

    /**
     * Generates the avatar URL
     * @param UserInterface|null $user
     * @param AvatarSize|null $size
     * @return string
     */
    public function generate(?UserInterface $user = null, ?AvatarSize $size = null)
    {
        return $this->urlGenerator->generate('bytes_avatarbundle_multiavatar', ['id' => $this->getUserId($user)]);
    }
}