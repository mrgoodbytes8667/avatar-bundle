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
    /**
     * @var array
     */
    private $results = [];

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

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param array $results
     * @return $this
     */
    public function setResults(array $results): self
    {
        $this->results = $results;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getFilters(): ?array
    {
        return $this->filters;
    }

    /**
     * @param array|null $filters
     * @return $this
     */
    public function setFilters(?array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return bool
     */
    public function isForce(): bool
    {
        return $this->force;
    }

    /**
     * @param bool $force
     * @return $this
     */
    public function setForce(bool $force): self
    {
        $this->force = $force;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     * @return $this
     */
    public function setCommand(string $command): self
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable|null $createdAt
     * @return $this
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt = null): self
    {
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        return $this;
    }
}