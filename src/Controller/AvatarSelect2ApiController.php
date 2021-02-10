<?php


namespace Bytes\AvatarBundle\Controller;


use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\AvatarBundle\Enums\AvatarSize;
use Bytes\AvatarBundle\Avatar\Avatars;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class AvatarSelect2ApiController
 * @package Bytes\AvatarBundle\Controller
 */
class AvatarSelect2ApiController extends AvatarApiController
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Avatars
     */
    private $avatars;

    /**
     * AvatarSelect2ApiController constructor.
     * @param Security $security
     * @param CacheManager $cacheManager
     * @param Avatars $avatars
     */
    public function __construct(Security $security, CacheManager $cacheManager, Avatars $avatars)
    {
        parent::__construct($security);
        $this->cacheManager = $cacheManager;
        $this->avatars = $avatars;
    }

    /**
     * @return JsonResponse
     */
    public function select2()
    {
        /** @var UserInterface $user */
        $user = $this->getUser();
        $content = [
            'results' => [
                $this->getSelect($this->avatars->gravatar($user, AvatarSize::s300()), 'Gravatar', $user->getAvatar()),
                $this->getSelect($this->avatars->multiAvatar($user), 'Multiavatar', $user->getAvatar()),
            ],
            'pagination' => [
                'more' => false
            ]
        ];

        return new JsonResponse($content);
    }

    /**
     * @param string $imageUrl
     * @param string $text
     * @param string|null $avatar
     * @return array
     */
    protected function getSelect(string $imageUrl, string $text, ?string $avatar)
    {
        return [
            'id' => $imageUrl,
            'text' => $text,
            'cachedImage' => $this->cacheManager->generateUrl($imageUrl, 'avatar_thumb_30x30', [], null, UrlGeneratorInterface::ABSOLUTE_PATH),
            'selected' => $imageUrl == $avatar,
        ];
    }
}