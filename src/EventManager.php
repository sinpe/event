<?php

namespace Sinpe\Event;

class EventManager implements EventManagerInterface
{
    /**
     * @var array[]
     */
    protected $events = [];

    /**
     * {@inheritDoc}
     */
    public function addListener($event, $callback, $priority = 999)
    {
        $events = [];
        if (isset($this->events[$event])) {
            $events = $this->events[$event];
        }

        $events[] = [
            'callback' => $callback,
            'priority' => $priority,
        ];

        usort($events, function ($a, $b) {
            if ($a['priority'] === $b['priority']) {
                return 0;
            }
            return $a['priority'] < $b['priority'] ? 1 : -1;
        });

        $this->events[$event] = $events;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function removeListener($event, $callback)
    {
        if (!isset($this->events[$event])) {
            return false;
        }

        $found  = false;

        $events = [];

        foreach ($this->events[$event] as $data) {
            if ($callback === $data['callback']) {
                $found = true;
                continue;
            }
            $events[] = $data;
        }

        $this->events[$event] = $events;

        return $found;
    }

    /**
     * {@inheritDoc}
     */
    public function clearListeners($event)
    {
        if (isset($this->events[$event])) {
            unset($this->events[$event]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch($event)
    {
        $name = $event;

        if ($event instanceof EventInterface) {
            $name = $event->getName();
        } else {
            $event = new Event($name);
        }

        if (!isset($this->events[$name])) {
            return false;
        }

        $result = null;

        // 支持通配符
        $names = $this->getEventNameParts($name);

        foreach ($names as $name) {
            if (isset($this->events[$name])) {
                foreach ($this->events[$name] as $data) {
                    // 
                    $result = $data['callback']($event, $this);

                    if ($result === false) {
                        break;
                    }
                }
            }
        }
        
        return $result;
    }

    /**
     * trigger别名
     */
    public function fire($event)
    {
        return $this->dispatch($event);
    }

    /**
     * 支持event名称带句号命名空间，支持通配符
     *
     * @param string $event
     * @param string[]
     */
    private function getEventNameParts($event) 
    {
        // 头尾句号无效
        $event = trim($event, '.');

        $parts = ['*'];
        $offset = 0;
        while ($offset = strpos($event, '.', $offset + 1)) {
            $parts[] = substr($event, 0, $offset) . '.*';
        }

        $parts[] = $event;

        return $parts;
    }
}
