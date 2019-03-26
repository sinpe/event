<?php

namespace Sinpe\Event;

class Event implements EventInterface
{
    /**
     * @var string
     */
    protected $name = null;

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

}
