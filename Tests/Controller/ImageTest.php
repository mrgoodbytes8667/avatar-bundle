<?php

namespace Bytes\AvatarBundle\Tests\Controller;

use Bytes\AvatarBundle\Controller\Image;
use Bytes\Common\Faker\TestFakerTrait;
use Bytes\ResponseBundle\Enums\ContentType;
use Bytes\Tests\Common\MockHttpClient\MockResponse;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Cache\Exception\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ImageTest
 * @package Bytes\AvatarBundle\Tests\Controller
 */
class ImageTest extends TestCase
{
    use TestFakerTrait;

    /**
     * @dataProvider provideSampleImages
     * @param $url
     */
    public function testGetImageAsPng($url)
    {
        $cache = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $client = new MockHttpClient(new MockResponse(file_get_contents($url)));
        $image = $this->setupImage($cache, $client, false, false);

        $response = $image->getImageAsPngFromUrl($this->faker->imageUrl());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imagePng->value, $response->headers->get('Content-Type'));
    }

    /**
     * @param AdapterInterface $cache
     * @param HttpClientInterface $client
     * @param bool $useSuccessCache
     * @param bool $useFallbackCache
     * @return Image
     */
    private function setupImage($cache, HttpClientInterface $client, bool $useSuccessCache, bool $useFallbackCache): Image
    {
        $image = new Image($cache, $useSuccessCache, '', 1, $useFallbackCache, '', 1, 1, 1, 1);
        $image->setClient($client);
        return $image;
    }

    /**
     * @dataProvider provideSampleImages
     * @param $url
     */
    public function testGetImageAsWebP($url)
    {
        $cache = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $client = new MockHttpClient(new MockResponse(file_get_contents($url)));
        $image = $this->setupImage($cache, $client, false, false);

        $response = $image->getImageAsWebPFromUrl($this->faker->imageUrl());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imageWebP->value, $response->headers->get('Content-Type'));
    }

    /**
     *
     */
    public function testGetImageAsPngWithCache()
    {
        $item = new CacheItem();
        $cache = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $cache->method('getItem')
            ->willReturn($item);
        $url = $this->getSampleImage();
        $client = new MockHttpClient(new MockResponse(file_get_contents($url)));
        $image = $this->setupImage($cache, $client, true, true);

        $response = $image->getImageAsPngFromUrl($this->faker->imageUrl());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imagePng->value, $response->headers->get('Content-Type'));
    }

    /**
     * @param string $extension
     * @return string
     */
    protected static function getSampleImage(string $extension = 'png'): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'sample.' . $extension;
    }

    /**
     *
     */
    public function testGetImageAsPngThrowException()
    {
        $cache = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $cache->method('getItem')
            ->willThrowException(new InvalidArgumentException());
        $url = $this->getSampleImage();
        $client = new MockHttpClient(new MockResponse(file_get_contents($url)));
        $image = $this->setupImage($cache, $client, true, true);

        $response = $image->getImageAsPngFromUrl($this->faker->imageUrl());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imagePng->value, $response->headers->get('Content-Type'));
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetImageAsInvalidUrl()
    {

        $this->expectException(ClientExceptionInterface::class);
        $client = new MockHttpClient(new MockResponse('', Response::HTTP_NOT_FOUND));

        $response = Image::getImageAs(ContentType::imagePng, url: $this->faker->imageUrl(), client: $client);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetImageAsInvalidUrlWithValidDefaultUrl()
    {
        $url = $this->getSampleImage();
        $client = new MockHttpClient([new MockResponse('', Response::HTTP_NOT_FOUND), new MockResponse(file_get_contents($url))]);

        $response = Image::getImageAs(ContentType::imagePng, url: $this->faker->imageUrl(), defaultUrl: $this->faker->imageUrl(), client: $client);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imagePng->value, $response->headers->get('Content-Type'));
    }

    /**
     *
     */
    public function testGetImageAsPngFromUrl()
    {
        $url = $this->getSampleImage();
        $client = new MockHttpClient(new MockResponse(file_get_contents($url)));
        $response = Image::getImageAsPng($this->faker->imageUrl(), client: $client);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imagePng->value, $response->headers->get('Content-Type'));
    }

    /**
     *
     */
    public function testGetImageAsWebPFromUrl()
    {
        $url = $this->getSampleImage();
        $client = new MockHttpClient(new MockResponse(file_get_contents($url)));
        $response = Image::getImageAsWebP($this->faker->imageUrl(), client: $client);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imageWebP->value, $response->headers->get('Content-Type'));
    }

    /**
     * @return Generator
     */
    public static function provideSampleImages()
    {
        yield 'png' =>  [self::getSampleImage('png')];
        yield 'jpg' =>  [self::getSampleImage('jpg')];
        yield 'gif' =>  [self::getSampleImage('gif')];
        yield 'webp' => [self::getSampleImage('webp')];
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testGetImageAsWithInvalidContentType()
    {
        $this->expectException(UnsupportedMediaTypeHttpException::class);
        $this->expectExceptionMessage('"getImageAs" can only accept content types of jpeg, png, gif, or webp.');
        Image::getImageAs(ContentType::json, $this->faker->imageUrl());
    }
}