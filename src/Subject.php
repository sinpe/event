<?php

namespace Sinpe\Event;

class Subject implements \SplSubject
{
    /**
     * @var SplObjectStorage
     */
    private $observers;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    /**
     * Undocumented function
     *
     * @param \SplObserver $observer
     * @return void
     */
    public function attach(\SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    /**
     * Undocumented function
     *
     * @param \SplObserver $observer
     * @return void
     */
    public function detach(\SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function notify()
    {
        foreach ($this->getObservers() as $observer) {
            $observer->update($this);
        }
    }

    /**
     * @return ObserversSet|\SplObserver[]
     */
    protected function getObservers()
    {
        return $this->observers;
    }
}