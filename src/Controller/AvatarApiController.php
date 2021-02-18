<?php

namespace Bytes\AvatarBundle\Controller;

use Bytes\AvatarBundle\Avatar\Gravatar;
use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\ResponseBundle\Enums\ContentType;
use Imagick;
use ImagickException;
use ImagickPixel;
use LogicException;
use Multiavatar\Multiavatar;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;
use function Symfony\Component\String\u;


/**
 * Class AvatarApiController
 * @package Bytes\AvatarBundle\Controller
 */
class AvatarApiController
{
    /**
     * @var Security
     */
    private $security;
    private string $multiAvatarSalt;
    private string $multiAvatarField;
    private string $nullUserReplacement;

    /**
     * AvatarApiController constructor.
     * @param Security $security
     * @param string $multiAvatarSalt
     * @param string $multiAvatarField
     * @param string $nullUserReplacement
     */
    public function __construct(Security $security, string $multiAvatarSalt = '', string $multiAvatarField = '', string $nullUserReplacement = '')
    {
        $this->security = $security;
        $this->multiAvatarSalt = $multiAvatarSalt;
        $this->multiAvatarField = $multiAvatarField;
        $this->nullUserReplacement = $nullUserReplacement;
    }

    /**
     * @param UserInterface|null $user
     * @param int $size
     * @return Response
     */
    public function gravatar(?UserInterface $user, int $size = 80): Response
    {
        if (!empty($user)) {
            return $this->getGravatar($user->getGravatar($size));
        } else {
            return $this->getGravatar(Gravatar::url($this->nullUserReplacement, $size));
        }
    }

    /**
     * @param string $url
     * @return Response
     */
    protected function getGravatar(string $url): Response
    {
        return new Response(file_get_contents($url),
            Response::HTTP_OK,
            ['content-type' => ContentType::imageJpg()]);
    }

    /**
     * @param UserInterface|null $user
     * @return Response
     * @throws ImagickException
     */
    public function multiAvatar(?UserInterface $user): Response
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
        return $this->getMultiAvatar($param);
    }

    /**
     * @param string $avatarId
     * @return Response
     * @throws ImagickException
     */
    protected function getMultiAvatar(string $avatarId)
    {
        $multiAvatar = new Multiavatar();

        $im = new Imagick();

        $im->setBackgroundColor(new ImagickPixel('transparent'));
        $im->setResolution(300, 300);
        $im->readImageBlob($multiAvatar(md5($avatarId . $this->multiAvatarSalt)));

        $im->setImageFormat("png32");

        return new Response(
            $im,
            Response::HTTP_OK,
            ['content-type' => ContentType::imagePng()]
        );
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

}
