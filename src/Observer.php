<?php

namespace Sinpe\Event;

abstract class Observer implements \SplObserver
{
    /**
     * 订阅者名称
     *
     * @var string $name
     */
    private $name;
    
    /**
     * ObserverCallable constructor.
     *
     * @param string $name
     */
    public function __construct(string $name) 
    {
        $this->name = $name;
    }

    abstract public function update(\SplSubject $subject);

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}