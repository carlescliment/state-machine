<?php

namespace carlescliment\StateMachine\Model;

interface StateMachineInterface
{
    public function addState($state);
    public function addTransition(TransitionInterface $transition);
    public function execute($transition_name, Statable $statable);
}