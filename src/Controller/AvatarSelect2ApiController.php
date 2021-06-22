<?php


namespace Bytes\AvatarBundle\Controller;


use Bytes\AvatarBundle\Avatar\Avatars;
use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\AvatarBundle\Enums\AvatarSize;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use function Symfony\Component\String\u;

/**
 * Class AvatarSelect2ApiController
 * @package Bytes\AvatarBundle\Controller
 */
class AvatarSelect2ApiController extends AvatarApiController
{
    /**
     * AvatarSelect2ApiController constructor.
     * @param Security $security
     * @param CacheManager $cacheManager
     * @param FilterManager $filterManager
     * @param DataManager $dataManager
     * @param Avatars $avatars
     */
    public function __construct(Security $security, private CacheManager $cacheManager, private FilterManager $filterManager, private DataManager $dataManager, private Avatars $avatars)
    {
        parent::__construct($security);
    }

    /**
     * @return JsonResponse
     */
    public function select2()
    {
        /** @var UserInterface $user */
        $user = $this->getUser();
        $content = [];
        foreach ($this->avatars->getAllTypes() as $type => $generator) {
            try {
                $results = $this->getSelect($generator->generate($user, AvatarSize::s300()), u($type)->title(), $user->getAvatar());
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
     * @param string $imageUrl
     * @param string $text
     * @param string|null $avatar
     * @return array
     */
    protected function getSelect(string $imageUrl, string $text, ?string $avatar)
    {
        if (!$this->cacheManager->isStored($imageUrl, 'avatar_thumb_30x30')) {
            $this->cacheManager->store($this->filterManager->applyFilter($this->dataManager->find('avatar_thumb_30x30', $imageUrl), 'avatar_thumb_30x30'), $imageUrl, 'avatar_thumb_30x30');
        }
        return [
            'id' => $imageUrl,
            'text' => $text,
            'cachedImage' => $this->cacheManager->generateUrl($imageUrl, 'avatar_thumb_30x30', [], null, UrlGeneratorInterface::ABSOLUTE_PATH),
            'selected' => $imageUrl == $avatar,
        ];
    }
}