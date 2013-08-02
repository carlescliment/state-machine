<?php

namespace StateMachine\Test\Model;

use carlescliment\StateMachine\Model\StateMachine;

class StateMachineTest extends \PHPUnit_Framework_TestCase
{

    private $machine;

    public function setUp()
    {
        $this->machine = new StateMachine;
    }

    /**
     * @test
     */
    public function itDefinesStates()
    {
        // Arrange
        // Act
        $this->machine->addState('STATE_ONE');

        // Assert
        $this->assertTrue($this->machine->hasState('STATE_ONE'));
    }
}