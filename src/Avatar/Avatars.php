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
 *
 * @method string multiAvatar(?UserInterface $user = null)
 * @method string gravatar(?UserInterface $user = null, ?AvatarSize $size = null)
 */
class Avatars
{
    use AvatarTrait;

    /**
     * @var UserInterface
     */
    private UserInterface $user;

    /**
     * @var AvatarInterface[]
     */
    private $all = [];

    /**
     * Avatars constructor.
     * @param Security $security
     * @param UrlGeneratorInterface $urlGenerator
     * @param AvatarChain $locator
     */
    public function __construct(Security $security, protected AvatarChain $locator)
    {
        // grab the user, do a quick sanity check that one exists
        /** @var UserInterface $user */
        $user = $security->getUser();
        if (!empty($user)) {
            $this->user = $user;
        }
    }

    /**
     * @return AvatarInterface[]
     */
    public function getAllTypes(): array
    {
        if(!empty($this->all))
        {
            return $this->all;
        }
        $this->all = $this->locator->getInstances();
        return $this->all;
    }

    /**
     * @param null $user
     * @return string[]
     */
    public function all($user = null)
    {
        $return = [];
        foreach($this->getAllTypes() as $tag => $generator)
        {
            if($generator::supportsMultipleSizes())
            {
                foreach (AvatarSize::toValues() as $size) {
                    $return[] = $generator->generate($user, AvatarSize::from($size));
                }
            } else {
                $return[] = $generator->generate($user);
            }
        }
        return $return;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return string
     */
    public function __call(string $name, array $arguments)
    {
        if($this->locator->has($name))
        {
            return $this->locator->get($name)->generate(...$arguments);
        }
    }


}