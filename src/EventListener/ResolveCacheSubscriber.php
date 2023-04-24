<?php


namespace Bytes\AvatarBundle\EventListener;


use Bytes\AvatarBundle\Event\ResolveCacheEvent;
use Exception;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class ResolveCacheSubscriber
 * @package Bytes\AvatarBundle\EventListener
 */
#[\Symfony\Component\Messenger\Attribute\AsMessageHandler]
class ResolveCacheSubscriber implements EventSubscriberInterface
{
    /**
     * ResolveCacheSubscriber constructor.
     * @param FilterManager $filterManager
     * @param FilterService $filterService
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(private readonly FilterManager $filterManager, private readonly FilterService $filterService, private readonly EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * The code must not depend on runtime state as it will only be called at compile time.
     * All logic depending on runtime state must be put into the individual methods handling the events.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResolveCacheEvent::class => 'onResolveCache',
        ];
    }

    /**
     * @param ResolveCacheEvent $message
     */
    public function __invoke(ResolveCacheEvent $message)
    {
        $this->dispatcher->dispatch($message);
    }

    /**
     * @param ResolveCacheEvent $event
     * @return ResolveCacheEvent
     */
    public function onResolveCache(ResolveCacheEvent $event): ResolveCacheEvent
    {
        $filters = $event->getFilters() ?: array_keys($this->filterManager->getFilterConfiguration()->all());
        $path = $event->getPath();
        $results = [
            'status' => true,
            'totals' => [
                'results' => 0,
                'exceptions' => 0,
            ]
        ];
        foreach ($filters as $filter) {
            if ($event->isForce()) {
                $this->filterService->bustCache($path, $filter);
            }

            try {
                $result = $this->filterService->getUrlOfFilteredImage($path, $filter);
                $results['results'][$filter] = $result;
            } catch (NotLoadableException $e) {
                $results['exceptions'][$filter] = $e->getMessage();
            } catch (Exception $e) {
                $results['status'] = false;
                $results['exceptions'][$filter] = $e->getMessage();
                break;
            }
        }

        if(isset($results['results']))
        {
            $results['totals']['results'] = count($results['results']);
        }

        if(isset($results['exceptions']))
        {
            $results['totals']['exceptions'] = count($results['exceptions']);
        }

        $event->setResults($results);

        return $event;
    }
}