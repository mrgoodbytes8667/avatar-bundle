<?php


namespace Bytes\AvatarBundle\Message;


use DateTimeImmutable;
use Liip\ImagineBundle\Async\Commands;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Trait ResolveCacheTrait
 * @package Bytes\AvatarBundle\Message
 */
trait ResolveCacheTrait
{
    private string $path;
    private ?array $filters = null;
    private bool $force = false;
    private string $command = Commands::RESOLVE_CACHE;
    private ?DateTimeImmutable $createdAt = null;

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