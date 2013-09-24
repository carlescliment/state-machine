<?php

namespace carlescliment\StateMachine\Model;

interface TransitionInterface
{
    public function getName();
    public function isAppliableOn(Statable $statable);
    public function execute(Statable $statable);
    public function getPreviousState();
    public function getNextState();
}