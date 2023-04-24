<?php


namespace Bytes\AvatarBundle\Avatar;


use Bytes\AvatarBundle\Enums\AvatarSize;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;
use Symfony\Component\String\UnicodeString;
use function Symfony\Component\String\u;

/**
 * Class Avatar
 * @package Bytes\AvatarBundle\Avatar
 */
abstract class Avatar implements AvatarInterface
{
    use AvatarTrait;

    /**
     * Generates the avatar URL
     * @param null $user
     * @param AvatarSize|null $size
     * @return string
     */
    final public function generate($user = null, ?AvatarSize $size = null)
    {
        $url = $this->generatePath($user, $size);
        if (!($url instanceof UnicodeString)) {
            $url = u($url);
        }
        
        if ($url->startsWith('/')) {
            $url = $url->after('/');
        }
        
        return $url->toString();
    }

    /**
     * @param null $user
     * @param AvatarSize|null $size
     * @return UnicodeString|string|null
     * @throws NotLoadableException
     */
    abstract public function generatePath($user = null, ?AvatarSize $size = null): UnicodeString|string|null;
}