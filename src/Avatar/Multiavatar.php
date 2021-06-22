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
        $url = $this->urlGenerator->generate('bytes_avatarbundle_multiavatar', ['id' => $this->getUserId($user) ?? 'abc123']);
        return $url;
    }

    /**
     * Does this generator support multiple sizes?
     * @return bool
     */
    public static function supportsMultipleSizes(): bool
    {
        return false;
    }
}