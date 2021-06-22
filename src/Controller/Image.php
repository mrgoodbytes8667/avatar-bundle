<?php


namespace Bytes\AvatarBundle\Controller;


use Bytes\ResponseBundle\Enums\ContentType;
use DateInterval;
use Psr\Cache\CacheException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use function Symfony\Component\String\u;

/**
 * Class Image
 * @package Bytes\AvatarBundle\Controller
 */
class Image
{
    /**
     * @var DateInterval
     */
    private DateInterval $expiresAfter;

    /**
     * Image constructor.
     * @param AdapterInterface $cache
     * @param bool $useCache
     * @param string $cachePrefix
     * @param int $cacheDuration
     */
    public function __construct(private AdapterInterface $cache, private bool $useCache, private string $cachePrefix, int $cacheDuration)
    {
        $expiresAfter = DateInterval::createFromDateString(sprintf('%d minutes', $cacheDuration));
        if (!$expiresAfter) {
            $this->useCache = false;
        } else {
            $this->expiresAfter = $expiresAfter;
        }
    }

    /**
     * @param string $url
     * @return Response
     */
    public function getImageAsPngFromUrl(string $url): Response
    {
        if (!$this->useCache) {
            return static::getImageAsPng($url);
        }
        try {
            $cacheKey = u($this->cachePrefix)->append('.getImageAsPngFromUrl.')->append(urlencode($url))->append('.contents')->toString();
            $item = $this->cache->getItem($cacheKey);
            if (!$item->isHit()) {
                $data = file_get_contents($url);
                $item->set($data);
                $item->expiresAfter($this->expiresAfter);
                $this->cache->save($item);
            }
            $data = $item->get();

            return static::getImageAsPng($url, $data);
        } catch (CacheException) {
            $this->useCache = false;
            return static::getImageAsPng($url);
        }
    }

    /**
     * @param string $url
     * @param string|null $data
     * @return Response
     */
    public static function getImageAsPng(string $url, ?string $data = null): Response
    {
        $data ??= file_get_contents($url);
        $info = getimagesizefromstring($data);
        if (isset($info['mime'])) {
            if ($info['mime'] === ContentType::imagePng()->value) {
                return new Response($data,
                    Response::HTTP_OK,
                    ['content-type' => ContentType::imagePng()]);
            }
        }
        $im = imagecreatefromstring($data);
        if ($im !== false) {
            ob_start();
            imagepng($im);
            imagedestroy($im);
            $image_data = ob_get_contents();
            ob_end_clean();
            return new Response($image_data,
                Response::HTTP_OK,
                ['content-type' => ContentType::imagePng()]);
        } else {
            throw new UnsupportedMediaTypeHttpException('');
        }
    }
}