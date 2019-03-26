<?php

namespace Sinpe\Event;

class Event implements EventInterface
{
    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var bool
     */
    protected $isPropagationStopped = false;

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        if (!$this->name) {
            throw new \Exception(sprintf(
                'please set a event name for "name" property in %s.',
                get_class($this)
            ));
        }

        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function stopPropagation($flag)
    {
        $this->isPropagationStopped = (bool)$flag;
    }

    /**
     * {@inheritDoc}
     */
    public function isPropagationStopped()
    {
        return $this->isPropagationStopped;
    }
}
