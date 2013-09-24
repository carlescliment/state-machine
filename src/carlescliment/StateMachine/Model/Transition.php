<?php

namespace carlescliment\StateMachine\Model;

use carlescliment\StateMachine\Exception\InvalidTransitionException;

class Transition implements TransitionInterface
{

    private $name;
    private $previous;
    private $next;

    public function __construct($name, $previous, $next)
    {
        $this->name = $name;
        $this->previous = $previous;
        $this->next = $next;
    }

    public function getName()
    {
        return $this->name;
    }


    public function getPreviousState()
    {
        return $this->previous;
    }


    public function getNextState()
    {
        return $this->previous;
    }


    public function isAppliableOn(Statable $statable)
    {
        return $statable->getState() == $this->previous;
    }


    public function execute(Statable $statable)
    {
        if (!$this->isAppliableOn($statable)) {
            $message = sprintf('Unable to transite from %s to %s. Expected state %s',
                $statable->getState(), $this->next, $this->previous);
            throw new InvalidTransitionException($message);
        }
        $statable->setState($this->next);
    }
}
