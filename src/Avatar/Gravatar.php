<?php


namespace Bytes\AvatarBundle\Avatar;


use Bytes\AvatarBundle\Entity\UserInterface;
use Bytes\AvatarBundle\Enums\AvatarSize;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\String\u;

/**
 * Class Gravatar
 * @package Bytes\AvatarBundle\Avatar
 */
class Gravatar implements AvatarInterface
{
    use AvatarTrait;

    /**
     * Get a Gravatar URL
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    public static function url(string $email, $s = 80)
    {
        return static::get($email, $s, 'retro', 'g');
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    public static function get(string $email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /**
     * @param UserInterface|null $user
     * @param AvatarSize|null $size
     * @return string
     */
    public function generate(?UserInterface $user = null, ?AvatarSize $size = null)
    {
        $size = $size ?? AvatarSize::s80();
        $url = u($this->urlGenerator->generate('bytes_avatarbundle_gravatar', ['id' => $this->getUserId($user) ?? 'abc123', 'size' => $size->value]));
        if($url->startsWith('/'))
        {
            $url = $url->after('/');
        }
        return $url->toString();
    }

    /**
     * Does this generator support multiple sizes?
     * @return bool
     */
    public static function supportsMultipleSizes(): bool
    {
        return true;
    }
}