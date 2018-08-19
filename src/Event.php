<?php

namespace Sinpe\Event;

class Event implements EventInterface
{
    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var null|string|object
     */
    protected $target = null;

    /**
     * @var mixed[]
     */
    protected $params = null;

    /**
     * @var bool
     */
    protected $isPropagationStopped = false;

    /**
     * @param string|null $name
     * @param null|string|object $target
     * @param mixed[] $params
     */
    public function __construct($name = null, $target = null, array $params = [])
    {
        if (null !== $name) {
            $this->setName($name);
        }

        $this->setTarget($target);
        $this->setParams($params);
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {
        if (!preg_match("/^[A-Za-z0-9_\.]+$/", $name)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Event name "%s" is invalid. Only alpha-numeric characters, '
                    .'underscores, and periods allowed.',
                    $name
                )
            );
        }

        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * {@inheritDoc}
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * {@inheritDoc}
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * {@inheritDoc}
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * {@inheritDoc}
     */
    public function getParam($name)
    {
        if (isset($this->params[$name]) || array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function stopPropagation($flag)
    {
        $this->isPropagationStopped = (bool) $flag;
    }

    /**
     * {@inheritDoc}
     */
    public function isPropagationStopped()
    {
        return $this->isPropagationStopped;
    }
}
