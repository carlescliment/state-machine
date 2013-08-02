<?php

namespace carlescliment\StateMachine\Model;

interface TransitionInterface
{
    public function transit(Statable $statable);
}