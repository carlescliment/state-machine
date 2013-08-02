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


    /**
     * @test
     */
    public function itDefinesTransitions()
    {
        // Arrange
        $this->machine->addState('STATE_ONE');
        $this->machine->addState('STATE_TWO');
        $transition = $this->getTransitionStub('TRANSITION_NAME', 'STATE_ONE', 'STATE_TWO');

        // Act
        $this->machine->addTransition($transition);

        // Assert
        $this->assertTrue($this->machine->hasTransition('TRANSITION_NAME'));
    }


    /**
     * @test
     * @expectedException carlescliment\StateMachine\Exception\InvalidTransitionException
     */
    public function itRaisesAnErrorWhenAddingATransitionWithInvalidPreviousState()
    {
        // Arrange
        $this->machine->addState('STATE_ONE');
        $this->machine->addState('STATE_TWO');
        $transition = $this->getTransitionStub('TRANSITION_NAME', 'UNEXISTING', 'STATE_TWO');

        // Act
        $this->machine->addTransition($transition);

    }


    /**
     * @test
     * @expectedException carlescliment\StateMachine\Exception\InvalidTransitionException
     */
    public function itRaisesAnErrorWhenAddingATransitionWithInvalidNextState()
    {
        // Arrange
        $this->machine->addState('STATE_ONE');
        $this->machine->addState('STATE_TWO');
        $transition = $this->getTransitionStub('TRANSITION_NAME', 'STATE_ONE', 'UNEXISTING');

        // Act
        $this->machine->addTransition($transition);
    }


    /**
     * @test
     */
    public function itExecutesTransitionsInStatableObjects()
    {
        // Arrange
        $this->machine->addState('STATE_ONE');
        $this->machine->addState('STATE_TWO');
        $transition = $this->getTransitionStub('TRANSITION_NAME', 'STATE_ONE', 'STATE_TWO');
        $this->machine->addTransition($transition);
        $statable = $this->getMock('carlescliment\StateMachine\Model\Statable');

        // Expect
        $transition->expects($this->once())
            ->method('execute')
            ->with($statable);

        // Act
        $this->machine->execute('TRANSITION_NAME', $statable);
    }

    /**
     * @test
     * @expectedException carlescliment\StateMachine\Exception\InvalidTransitionException
     */
    public function itRaisesAnErrorWhenExecutingUnexistingTransitions()
    {
        // Arrange
        $statable = $this->getMock('carlescliment\StateMachine\Model\Statable');

        // Act
        $this->machine->execute('UNEXISTING_TRANSITION', $statable);
    }



    private function getTransitionStub($transition_name, $previous_state, $next_state)
    {
        $transition = $this->getMock('carlescliment\StateMachine\Model\TransitionInterface');
        $transition->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($transition_name));
        $transition->expects($this->any())
            ->method('getPreviousState')
            ->will($this->returnValue($previous_state));
        $transition->expects($this->any())
            ->method('getNextState')
            ->will($this->returnValue($next_state));
        return $transition;
    }        
}