<?php


namespace Bytes\AvatarBundle\MessageHandler;


use Bytes\AvatarBundle\Message\ResolveCacheMessage;
use Liip\ImagineBundle\Async\ResolveCache;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class ResolveCacheHandler
 * @package Bytes\AvatarBundle\MessageHandler
 */
class ResolveCacheHandler implements MessageHandlerInterface
{
    /**
     * @param ResolveCacheMessage $message
     */
    public function __invoke(ResolveCacheMessage $message)
    {
        new ResolveCache($message->getPath(), $message->getFilters(), $message->isForce());
    }
}