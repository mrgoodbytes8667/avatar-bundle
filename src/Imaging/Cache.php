<?php


namespace Bytes\AvatarBundle\Imaging;


use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

/**
 * Class Cache
 * @package Bytes\AvatarBundle\Imaging
 */
class Cache
{
    /**
     * Cache constructor.
     * @param CacheManager $cacheManager
     * @param FilterManager $filterManager
     * @param DataManager $dataManager
     */
    public function __construct(private CacheManager $cacheManager, private FilterManager $filterManager, private DataManager $dataManager)
    {
    }

    /**
     * @param string $imageUrl
     * @param array $filters
     * @param bool $force
     */
    public function warmup(string $imageUrl, array $filters = [], bool $force = false)
    {
        foreach ($filters ?? $this->filterManager->getFilterConfiguration()->all() as $filter) {
            if ($force || !$this->cacheManager->isStored($imageUrl, $filter)) {
                $this->cacheManager->store($this->filterManager->applyFilter($this->dataManager->find($filter, $imageUrl), $filter), $imageUrl, $filter);
            }
        }
    }
}