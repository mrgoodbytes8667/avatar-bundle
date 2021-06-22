<?php


namespace Bytes\AvatarBundle\Avatar;


/**
 * Class AvatarChain
 * @package Bytes\AvatarBundle\Avatar
 */
class AvatarChain
{
    /**
     * AvatarChain constructor.
     * @param AvatarInterface[] $instances
     */
    public function __construct(private array $skips = [], private array $instances = [])
    {
    }

    /**
     * @return AvatarInterface[]
     */
    public function getInstances(): array
    {
        return $this->instances;
    }

    /**
     * @param array $instances
     * @return $this
     */
    public function setInstances(array $instances): self
    {
        $this->instances = $instances;
        return $this;
    }

    /**
     * @param AvatarInterface $instance
     * @param string|null $alias
     * @return $this
     */
    public function addInstance(AvatarInterface $instance, ?string $alias = null): self
    {
        $alias ??= $instance::class;
        if(!array_key_exists($alias, $this->skips))
        {
            $this->instances[$alias] = $instance;
        }

        return $this;
    }

    /**
     * @param string $tag
     * @return bool
     */
    public function has(string $tag): bool
    {
        return array_key_exists($tag, $this->instances);
    }

    /**
     * @param string $tag
     * @return AvatarInterface
     */
    public function get(string $tag): AvatarInterface
    {
        if(!$this->has($tag))
        {
            throw new \InvalidArgumentException(sprintf('The key "%s" is not registered.', $tag));
        }
        return $this->instances[$tag];
    }
}