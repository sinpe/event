<?php
 /*
 * This file is part of the long/event package.
 *
 * (c) Sinpe <support@sinpe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Sinpe\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var ListenerProviderInterface
     */
    private $listenerProvider;

    /**
     * __construct
     *
     * @param ListenerProviderInterface $listenerProvider
     */
    public function __construct(ListenerProviderInterface $listenerProvider = null)
    {
        if ($listenerProvider) {
            $this->listenerProvider = $listenerProvider;
        } else {
            $this->listenerProvider = new ListenerProvider;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(object $event)
    {
        $stoppable = $event instanceof StoppableEventInterface;

        if ($stoppable && $event->isPropagationStopped()) {
            return $event;
        }

        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {

            call_user_func($listener, $event, $this);

            if ($stoppable && $event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }

    /**
     * __call
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->listenerProvider, $name)) {
            return call_user_func_array([$this->listenerProvider, $name], $arguments);
        }

        throw new \Exception(sprintf('%s:%s not exists.', get_class($this), $name));
    }
    
}
