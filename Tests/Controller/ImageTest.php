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
        $image = new Image($cache, $client, false, '', 1);

        $response = $image->getImageAsPngFromUrl($this->faker->imageUrl());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imagePng(), $response->headers->get('Content-Type'));
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
        $image = new Image($cache, $client, true, '', 1);

        $response = $image->getImageAsPngFromUrl($this->faker->imageUrl());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imagePng(), $response->headers->get('Content-Type'));
    }

    /**
     * @param string $extension
     * @return string
     */
    protected function getSampleImage(string $extension = 'png'): string
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
        $image = new Image($cache, $client, true, '', 1);

        $response = $image->getImageAsPngFromUrl($this->faker->imageUrl());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imagePng(), $response->headers->get('Content-Type'));
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
        $this->assertEquals(ContentType::imagePng(), $response->headers->get('Content-Type'));
    }

    /**
     *
     */
    public function testGetImageAsPngInvalidFile()
    {
        $this->expectWarning();
        $this->expectWarningMessage('Data is not in a recognized format');
        $cache = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $url = $this->getSampleImage('txt');
        $client = new MockHttpClient(new MockResponse(file_get_contents($url)));
        $image = new Image($cache, $client, false, '', 1);

        $image->getImageAsPngFromUrl($this->faker->imageUrl());
    }

    /**
     * @return Generator
     */
    public function provideSampleImages()
    {
        yield [$this->getSampleImage('png')];
        yield [$this->getSampleImage('jpg')];
    }
}
