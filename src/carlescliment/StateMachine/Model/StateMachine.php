<?php

namespace carlescliment\StateMachine\Model;


class StateMachine
{
    private $states;

    public function __construct()
    {
        $this->states = array();
    }

    public function addState($state) {
        $this->states[$state] = true;
    }

    public function hasState($state) {
        return isset($this->states[$state]);
    }
}