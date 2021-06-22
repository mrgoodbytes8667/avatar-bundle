<?php

namespace Bytes\AvatarBundle\Tests\Controller;

use Bytes\AvatarBundle\Controller\Image;
use Bytes\Common\Faker\TestFakerTrait;
use Bytes\ResponseBundle\Enums\ContentType;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Cache\Exception\InvalidArgumentException;
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
        $image = new Image($cache, false, '', 1);

        $response = $image->getImageAsPngFromUrl($url);

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
        $image = new Image($cache, true, '', 1);
        $url = $this->getSampleImage();

        $response = $image->getImageAsPngFromUrl($url);

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
        $image = new Image($cache, true, '', 1);
        $url = $this->getSampleImage();

        $response = $image->getImageAsPngFromUrl($url);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ContentType::imagePng(), $response->headers->get('Content-Type'));
    }

    /**
     *
     */
    public function testGetImageAsPngFromUrl()
    {
        $url = $this->getSampleImage();
        $response = Image::getImageAsPng($url);

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
        $image = new Image($cache, false, '', 1);
        $url = $this->getSampleImage('txt');

        $image->getImageAsPngFromUrl($url);
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
