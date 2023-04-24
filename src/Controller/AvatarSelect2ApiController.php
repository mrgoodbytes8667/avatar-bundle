<?php


namespace Bytes\AvatarBundle\Controller;


use Bytes\AvatarBundle\Avatar\Avatars;
use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\AvatarBundle\Enums\AvatarSize;
use Bytes\AvatarBundle\Imaging\Cache;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\String\u;

/**
 * Class AvatarSelect2ApiController
 * @package Bytes\AvatarBundle\Controller
 */
class AvatarSelect2ApiController
{
    /**
     * @param Security $security
     * @param CacheManager $cacheManager
     * @param Cache $cache
     * @param Avatars $avatars
     * @param string $filter
     */
    public function __construct(private readonly Security $security, private readonly CacheManager $cacheManager, private readonly Cache $cache, private readonly Avatars $avatars, private readonly string $filter)
    {
    }

    /**
     * @return JsonResponse
     */
    public function select2()
    {
        /** @var UserInterface $user */
        $user = $this->getUser();
        $content = [];
        foreach ($this->avatars->getAllTypes() as $type => $avatar) {
            try {
                $results = $this->getSelect($avatar->generate($user, AvatarSize::s300), u($type)->title(), $user->getAvatar());
                $content['results'][] = $results;
            } catch (NotLoadableException) {
            }
        }

        $content['pagination'] = [
            'more' => false
        ];

        return new JsonResponse($content);
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface|object|null
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
     * @param string $imageUrl
     * @param string $text
     * @param string|null $avatar
     * @return array
     */
    protected function getSelect(string $imageUrl, string $text, ?string $avatar)
    {
        if (empty($imageUrl)) {
            throw new NotLoadableException();
        }

        $this->cache->warmup($imageUrl, [$this->filter]);
        return [
            'id' => $imageUrl,
            'text' => $text,
            'cachedImage' => $this->cacheManager->generateUrl($imageUrl, $this->filter, [], null, UrlGeneratorInterface::ABSOLUTE_PATH),
            'selected' => $imageUrl == $avatar,
        ];
    }
}
