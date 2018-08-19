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
    public function attach($event, $callback, $priority = 999)
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
    public function detach($event, $callback)
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
    public function trigger($event, $target = null, array $params = [])
    {
        $name = $event;

        if ($event instanceof EventInterface) {
            $name = $event->getName();
        } else {
            $event = new Event($name, $target, $params);
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
                    $result = $data['callback']($event, $result);
                    if ($event->isPropagationStopped()) {
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
    public function fire($event, $target = null, array $params = [])
    {
        return $this->trigger($event, $target, $params);
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
