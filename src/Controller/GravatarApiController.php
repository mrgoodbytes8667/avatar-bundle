<?php

namespace Bytes\AvatarBundle\Controller;

use Bytes\AvatarBundle\Avatar\Gravatar;
use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\ResponseBundle\Enums\ContentType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


/**
 * Class GravatarApiController
 * @package Bytes\AvatarBundle\Controller
 */
class GravatarApiController
{
    use AvatarControllerTrait;

    /**
     * GravatarApiController constructor.
     * @param string $nullUserReplacement
     */
    public function __construct(private readonly string $nullUserReplacement = '')
    {
    }

    /**
     * @param UserInterface|null $user
     * @param int $size
     * @return Response
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function gravatarPngAction(?UserInterface $user, int $size = 80): Response
    {
        return $this->gravatar($user, $size, ContentType::imagePng);
    }

    /**
     * @param UserInterface|null $user
     * @param int $size
     * @param ContentType $contentType
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function gravatar(?UserInterface $user, int $size, ContentType $contentType): Response
    {
        if (!empty($user)) {
            return $this->getGravatar($user->getGravatar($size), $contentType);
        } else {
            return $this->getGravatar(Gravatar::url($this->nullUserReplacement, $size), $contentType);
        }
    }

    /**
     * @param string $url
     * @param ContentType $contentType
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function getGravatar(string $url, ContentType $contentType): Response
    {
        return $this->image->getImageAsFromUrl($url, $contentType);
    }

    /**
     * @param UserInterface|null $user
     * @param int $size
     * @return Response
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function gravatarWebPAction(?UserInterface $user, int $size = 80): Response
    {
        return $this->gravatar($user, $size, ContentType::imageWebP);
    }
}