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

class Subject implements \SplSubject
{
    /**
     * @var SplObjectStorage
     */
    private $observers;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    /**
     * attach
     *
     * @param \SplObserver $observer
     * @return void
     */
    public function attach(\SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    /**
     * detach
     *
     * @param \SplObserver $observer
     * @return void
     */
    public function detach(\SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    /**
     * notify
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