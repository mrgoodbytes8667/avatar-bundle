<?php


namespace Bytes\AvatarBundle\Controller;


use Bytes\ResponseBundle\Enums\ContentType;
use DateInterval;
use Psr\Cache\CacheException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
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
     * @param HttpClientInterface $client
     * @param bool $useCache
     * @param string $cachePrefix
     * @param int $cacheDuration
     */
    public function __construct(private AdapterInterface $cache, private HttpClientInterface $client, private bool $useCache, private string $cachePrefix, int $cacheDuration)
    {
        $expiresAfter = DateInterval::createFromDateString(sprintf('%d minutes', $cacheDuration));
        if (!$expiresAfter) {
            $this->useCache = false;
        } else {
            $this->expiresAfter = $expiresAfter;
        }
    }

    //region getImageAs

    /**
     * @param string $url
     * @param string|null $data
     * @param string|null $defaultUrl Fallback/default url if $url does not resolve, ignored if data or defaultData is provided
     * @param string|null $defaultData Fallback/default data if $url does not resolve, ignored if data is provided
     * @param HttpClientInterface|null $client
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public static function getImageAsPng(string $url, ?string $data = null, ?string $defaultUrl = null, ?string $defaultData = null, ?HttpClientInterface $client = null): Response
    {
        return static::getImageAs(ContentType::imagePng(), $url, $data, defaultUrl: $defaultUrl, defaultData: $defaultData, client: $client);
    }

    /**
     * @param ContentType $contentType = [ContentType::imageJpg(), ContentType::imagePng(), ContentType::imageWebP()][$any]
     * @param string $url
     * @param string|null $data
     * @param string|null $defaultUrl Fallback/default url if $url does not resolve, ignored if data or defaultData is provided
     * @param string|null $defaultData Fallback/default data if $url does not resolve, ignored if data is provided
     * @param HttpClientInterface|null $client
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public static function getImageAs(ContentType $contentType, string $url, ?string $data = null, ?string $defaultUrl = null, ?string $defaultData = null, ?HttpClientInterface $client = null): Response
    {
        if (!$contentType->equals(ContentType::imageJpg(), ContentType::imagePng(), ContentType::imageWebP())) {
            throw new UnsupportedMediaTypeHttpException(sprintf('"%s" can only accept content types of jpeg, png, or webp.', __FUNCTION__));
        }
        if (empty($data)) {
            $client ??= HttpClient::create();
            $response = $client->request('GET', $url);
            if (static::isSuccess($response)) {
                $data = $response->getContent();
            } else {
                if (!empty($defaultUrl) && empty($defaultData)) {
                    $response = $client->request('GET', $defaultUrl);
                    $data = $response->getContent();
                } elseif (!empty($defaultData)) {
                    $data = $defaultData;
                } else {
                    // To simply throw the error
                    $data = $response->getContent();
                }
            }

        }
        $info = getimagesizefromstring($data);
        if (isset($info['mime'])) {
            if ($info['mime'] === $contentType->value) {
                return new Response($data,
                    Response::HTTP_OK,
                    ['content-type' => $contentType]);
            }
        }
        $im = imagecreatefromstring($data);
        if ($im !== false) {
            ob_start();
            switch ($contentType) {
                case ContentType::imageJpg():
                    imagejpeg($im);
                    break;
                case ContentType::imagePng():
                    imagepng($im);
                    break;
                default:
                    imagepalettetotruecolor($im);
                    imagewebp($im);
                    break;
            }
            imagedestroy($im);
            $image_data = ob_get_contents();
            ob_end_clean();
            return new Response($image_data,
                Response::HTTP_OK,
                ['content-type' => $contentType]);
        } else {
            throw new UnsupportedMediaTypeHttpException('Unable to process image');
        }
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     */
    private static function isSuccess(ResponseInterface $response): bool
    {
        try {
            $code = $response->getStatusCode();
            return $code >= 200 && $code < 300;
        } catch (TransportExceptionInterface) {
            return false;
        }
    }
    //endregion

    //region getImageAsFromUrl

    /**
     * @param string $url
     * @param string|null $data
     * @param string|null $defaultUrl Fallback/default url if $url does not resolve, ignored if data or defaultData is provided
     * @param string|null $defaultData Fallback/default data if $url does not resolve, ignored if data is provided
     * @param HttpClientInterface|null $client
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public static function getImageAsWebP(string $url, ?string $data = null, ?string $defaultUrl = null, ?string $defaultData = null, ?HttpClientInterface $client = null): Response
    {
        return static::getImageAs(ContentType::imageWebP(), $url, $data, defaultUrl: $defaultUrl, defaultData: $defaultData, client: $client);
    }

    /**
     * @param string $url
     * @param string|null $defaultUrl Fallback/default url if $url does not resolve
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getImageAsPngFromUrl(string $url, ?string $defaultUrl = null): Response
    {
        return $this->getImageAsFromUrl($url, ContentType::imagePng(), defaultUrl: $defaultUrl);
    }

    /**
     * @param string $url
     * @param string|null $defaultUrl Fallback/default url if $url does not resolve, ignored if data or defaultData is provided
     * @param string|null $defaultData Fallback/default data if $url does not resolve, ignored if data is provided
     * @param ContentType $contentType = [ContentType::imageJpg(), ContentType::imagePng(), ContentType::imageWebP()][$any]
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getImageAsFromUrl(string $url, ContentType $contentType, ?string $defaultUrl = null, ?string $defaultData = null): Response
    {
        $contentType ??= ContentType::imagePng();
        if (!$this->useCache) {
            return static::getImageAs($contentType, $url, defaultUrl: $defaultUrl, defaultData: $defaultData, client: $this->client);
        }
        try {
            $cacheKey = u($this->cachePrefix)->append('.getImageAsFromUrl.')->append(urlencode($url))->append('.contents')->toString();
            $item = $this->cache->getItem($cacheKey);
            if (!$item->isHit()) {
                $response = $this->client->request('GET', $url);
                $item->expiresAfter($this->expiresAfter);
                $data = $response->getContent();
                $item->set($data);
                $this->cache->save($item);
            }
            $data = $item->get();

            return static::getImageAs($contentType, $url, $data, defaultUrl: $defaultUrl, defaultData: $defaultData, client: $this->client);
        } catch (CacheException) {
            $this->useCache = false;
            return static::getImageAs($contentType, $url, defaultUrl: $defaultUrl, defaultData: $defaultData, client: $this->client);
        }
    }
    //endregion

    /**
     * @param string $url
     * @param string|null $defaultUrl Fallback/default url if $url does not resolve
     * @return Response
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getImageAsWebPFromUrl(string $url, ?string $defaultUrl = null): Response
    {
        return $this->getImageAsFromUrl($url, ContentType::imageWebP(), defaultUrl: $defaultUrl);
    }
}