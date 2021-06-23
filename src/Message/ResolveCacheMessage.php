<?php


namespace Bytes\AvatarBundle\Message;


use DateTimeImmutable;
use Liip\ImagineBundle\Async\Commands;

/**
 * Class ResolveCacheMessage
 * @package Bytes\AvatarBundle\Message
 */
class ResolveCacheMessage
{
    use ResolveCacheTrait;

    /**
     * ResolveCacheMessage constructor.
     * @param string $path
     * @param array|null $filters
     * @param bool $force
     * @param string $command
     * @param DateTimeImmutable|null $createdAt
     */
    public function __construct(string $path, ?array $filters = null, bool $force = false, string $command = Commands::RESOLVE_CACHE, ?DateTimeImmutable $createdAt = null)
    {
        if (empty($createdAt)) {
            $this->createdAt = new DateTimeImmutable();
        }
        $this->set($path, $filters, $force, $command);
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