<?php

namespace Bytes\AvatarBundle\Tests\Imaging;

use Bytes\AvatarBundle\Imaging\Cache;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use PHPUnit\Framework\TestCase;

/**
 * Class CacheTest
 * @package Bytes\AvatarBundle\Tests\Imaging
 */
class CacheTest extends TestCase
{
    /**
     *
     */
    public function testGetFilters()
    {
        $cacheManager = $this->getMockBuilder(CacheManager::class)->disableOriginalConstructor()->getMock();
        $filterManager = $this->getMockBuilder(FilterManager::class)->disableOriginalConstructor()->getMock();
        $dataManager = $this->getMockBuilder(DataManager::class)->disableOriginalConstructor()->getMock();

        $cache = new Cache($cacheManager, $filterManager, $dataManager);

        $this->assertEmpty($cache->getFilters());
        $this->assertEmpty($cache->getFilters(['abc123']));
    }
}
