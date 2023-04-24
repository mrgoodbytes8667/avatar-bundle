<?php

namespace Bytes\AvatarBundle\Controller;

use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\AvatarBundle\Enums\AvatarSize;
use Bytes\ResponseBundle\Enums\ContentType;
use Imagick;
use ImagickException;
use ImagickPixel;
use Multiavatar\Multiavatar;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function Symfony\Component\String\u;


/**
 * Class MultiAvatarApiController
 * @package Bytes\AvatarBundle\Controller
 */
class MultiAvatarApiController
{
    use AvatarControllerTrait;

    /**
     * @var int
     */
    private $avatarSize;

    /**
     * MultiAvatarApiController constructor.
     * @param HttpClientInterface $client
     * @param string $multiAvatarSalt
     * @param string $multiAvatarField
     * @param string $nullUserReplacement
     */
    public function __construct(private readonly HttpClientInterface $client, private readonly string $multiAvatarSalt = '', private readonly string $multiAvatarField = '', private readonly string $nullUserReplacement = '')
    {
        $this->avatarSize = AvatarSize::s300->value;
    }

    /**
     * @param UserInterface|null $user
     * @param int $size
     * @return Response
     * @throws ClientExceptionInterface
     * @throws ImagickException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function multiAvatarPngAction(?UserInterface $user, int $size = 80): Response
    {
        return $this->multiAvatar($user, $this->avatarSize, ContentType::imagePng);
    }

    /**
     * @param UserInterface|null $user
     * @param int $size
     * @param ContentType $contentType = [ContentType::imageJpg, ContentType::imagePng, ContentType::imageWebP][$any]
     * @return Response
     * @throws ClientExceptionInterface
     * @throws ImagickException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function multiAvatar(?UserInterface $user, int $size, ContentType $contentType): Response
    {
        $param = '';
        if (!empty($user)) {
            if ($user instanceof UserInterface) {
                $function = u($this->multiAvatarField)->prepend('get_')->camel()->toString();
                $param = $user->$function();
            }
        } else {
            $param = $this->nullUserReplacement;
        }
        
        return $this->getMultiAvatar($param, $size, $contentType);
    }

    /**
     * @param string $url
     * @param int $size
     * @param ContentType $contentType = [ContentType::imageJpg, ContentType::imagePng, ContentType::imageWebP][$any]
     * @return Response
     * @throws ClientExceptionInterface
     * @throws ImagickException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function getMultiAvatar(string $url, int $size, ContentType $contentType): Response
    {
        $multiAvatar = new Multiavatar();

        $im = new Imagick();

        $im->setBackgroundColor(new ImagickPixel('transparent'));
        $im->setResolution($size, $size);
        $im->readImageBlob($multiAvatar(md5($url . $this->multiAvatarSalt)));

        $im->setImageFormat("png32");

        return Image::getImageAs($contentType, $url, $im, $this->client);
    }

    /**
     * @param UserInterface|null $user
     * @param int $size
     * @return Response
     * @throws ClientExceptionInterface
     * @throws ImagickException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function multiAvatarWebPAction(?UserInterface $user, int $size = 80): Response
    {
        return $this->multiAvatar($user, $this->avatarSize, ContentType::imageWebP);
    }
}