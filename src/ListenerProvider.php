<?php
/*
 * This file is part of the long/event package.
 *
 * (c) Sinpe <support@sinpe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sinpe\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface
{
    /**
     * @var array
     */
    private $sorted = [];

    /**
     * @var array
     */
    private $listeners = [];

    /**
     * {@inheritDoc}
     */
    public function getListenersForEvent(object $event): iterable
    { 
        $eventType = get_class($event);

        $listeners = $this->listeners[$eventType] ?? [];

        if (!isset($this->sorted[$eventType])) {
            usort($listeners, function ($a, $b) {
                if ($a['priority'] === $b['priority']) {
                    return 0;
                }
                return $a['priority'] < $b['priority'] ? 1 : -1;
            });
            $this->sorted[$eventType] = true;
        }

        foreach ($listeners as $listener) {
            yield $listener;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addListener(string $eventType, callable $callable, $priority = 999)
    {
        $listeners = [];

        if (isset($this->listeners[$eventType])) {
            $listeners = $this->listeners[$eventType];
        }

        $listeners[] = [
            'callable' => $callable,
            'priority' => $priority,
        ];

        $this->listeners[$eventType] = $listeners;

        unset($this->sorted[$eventType]);
    }

    /**
     * {@inheritDoc}
     */
    public function removeListener(string $eventType, $callable)
    {
        if (!isset($this->listeners[$eventType])) {
            return;
        }

        $listeners = [];

        foreach ($this->listeners[$eventType] as $data) {
            if ($callable === $data['callable']) {
                continue;
            }
            $listeners[] = $data;
        }

        $this->listeners[$eventType] = $listeners;
    }

    /**
     * {@inheritDoc}
     */
    public function clearListeners(string $eventType)
    {
        if (isset($this->listeners[$eventType])) {
            unset($this->listeners[$eventType]);
        }
    }

}
