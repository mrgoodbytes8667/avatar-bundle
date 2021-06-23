<?php


namespace Bytes\AvatarBundle\Message;


use DateTimeImmutable;
use Liip\ImagineBundle\Async\Commands;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ResolveCacheEvent
 * @package Bytes\AvatarBundle\Message
 */
class ResolveCacheEvent extends Event
{
    use ResolveCacheTrait;

    /**
     * ResolveCacheEvent constructor.
     * @param string $path
     * @param array|null $filters
     * @param bool $force
     * @param string $command
     * @param DateTimeImmutable|null $createdAt
     */
    public function __construct(private string $path, private ?array $filters = null, private bool $force = false, private string $command = Commands::RESOLVE_CACHE, private ?DateTimeImmutable $createdAt = null)
    {
        if (empty($createdAt)) {
            $this->createdAt = new DateTimeImmutable();
        }
    }

    /**
     * @param string $path
     * @param array|null $filters
     * @param bool $force
     * @return static
     */
    public static function new(string $path, ?array $filters = null, bool $force = false): static
    {
        return new static($path, $filters, $force);
    }
}