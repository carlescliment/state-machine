<?php

namespace carlescliment\StateMachine\Model;

interface Statable
{

    public function setState($state);
    public function getState();

}