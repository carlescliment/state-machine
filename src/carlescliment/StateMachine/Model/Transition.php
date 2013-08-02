<?php

namespace carlescliment\StateMachine\Model;

use carlescliment\StateMachine\Exception\InvalidTransitionException;

class Transition implements TransitionInterface
{

    private $previous;
    private $next;

    public function __construct($previous, $next)
    {
        $this->previous = $previous;
        $this->next = $next;
    }


    public function transit(Statable $statable)
    {
        if ($statable->getState() !== $this->previous) {
            $message = sprintf('Unable to transite from %s to %s. Expected state %s',
                $statable->getState(), $this->next, $this->previous);
            throw new InvalidTransitionException($message);
        }
        $statable->setState($this->next);
    }
}