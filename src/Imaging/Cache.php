<?php


namespace Bytes\AvatarBundle\Imaging;


use Illuminate\Support\Arr;
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
    public function __construct(private readonly CacheManager $cacheManager, private readonly FilterManager $filterManager, private readonly DataManager $dataManager)
    {
    }

    /**
     * @param string $imageUrl
     * @param array $filters
     * @param bool $force
     */
    public function warmup(string $imageUrl, array $filters = [], bool $force = false)
    {
        foreach ($filters ?? $this->getFilters() as $filter) {
            if ($force || !$this->cacheManager->isStored($imageUrl, $filter)) {
                $this->cacheManager->store($this->filterManager->applyFilter($this->dataManager->find($filter, $imageUrl), $filter), $imageUrl, $filter);
            }
        }
    }

    /**
     * @param string[] $exclude
     * @return string[]
     */
    public function getFilters(array $exclude = []): array
    {
        $filters = array_keys($this->filterManager->getFilterConfiguration()->all() ?? []);
        if(!empty($exclude)) {
            return Arr::where($filters, function ($value) use ($exclude) {
                return !in_array($value, $exclude);
            });
        }

        return $filters;
    }
}