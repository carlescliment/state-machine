<?php

namespace carlescliment\StateMachine\Model;

use carlescliment\StateMachine\Exception\InvalidTransitionException;

class StateMachine implements StateMachineInterface
{

    private $states;
    private $transitions;

    public function __construct()
    {
        $this->states = array();
        $this->transitions = array();
    }


    public function addState($state) {
        $this->states[$state] = true;
    }


    public function addTransition(TransitionInterface $transition) {
        $this->assertTransitionStatesAreValid($transition);
        $this->transitions[$transition->getName()] = $transition;
    }

    public function execute($transition_name, Statable $statable)
    {
        if (!$this->hasTransition($transition_name)) {
            $message = sprintf('The transition %s has not been added to the state machine.',
                $transition_name);
            throw new InvalidTransitionException($message);
        }
        $this->transitions[$transition_name]->execute($statable);
    }


    private function assertTransitionStatesAreValid(TransitionInterface $transition)
    {
        if (!$this->hasState($transition->getPreviousState())) {
            $message = sprintf('The initial state %s in the transition %s has not been added to the state machine.',
                $transition->getPreviousState(), $transition->getName());
            throw new InvalidTransitionException($message);
        }
        
        if (!$this->hasState($transition->getNextState())) {
            $message = sprintf('The next state %s in the transition %s has not been added to the state machine.',
                $transition->getNextState(), $transition->getName());
            throw new InvalidTransitionException($message);
        }
    }


    public function hasState($state) {
        return isset($this->states[$state]);
    }

    public function hasTransition($transition_name) {
        return isset($this->transitions[$transition_name]);
    }    
}