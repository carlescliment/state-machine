<?php

namespace StateMachine\Test\Model;

use carlescliment\StateMachine\Model\StateMachine;

class StateMachineTest extends \PHPUnit_Framework_TestCase
{

    private $machine;

    public function setUp()
    {
        $this->machine = new StateMachine;
        $this->machine->addState('STATE_ONE');
        $this->machine->addState('STATE_TWO');
    }

    /**
     * @test
     */
    public function itBringsIfHasAParticularState()
    {
        // Arrange
        // Act
        $has_state = $this->machine->hasState('STATE_ONE');

        // Assert
        $this->assertTrue($has_state);
    }


    /**
     * @test
     */
    public function itDefinesTransitions()
    {
        // Arrange
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
        $transition = $this->addTransitionToMachine('TRANSITION_NAME', 'STATE_ONE', 'STATE_TWO');
        $statable = $this->getAppliableEntityOn($transition);

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


    /**
     * @test
     */
    public function itAllowsTheSameTransitionInTwoStartingStates()
    {
        // Arrange
        $this->machine->addState('STATE_THREE');
        $transition1 = $this->addTransitionToMachine('TRANSITION_NAME', 'STATE_ONE', 'STATE_THREE');
        $transition2 = $this->addTransitionToMachine('TRANSITION_NAME', 'STATE_TWO', 'STATE_THREE');
        $statable1 = $this->getAppliableEntityOn($transition1);
        $statable2 = $this->getMock('carlescliment\StateMachine\Model\Statable');


        // Expect
        $transition1->expects($this->at(1))
            ->method('execute')
            ->with($statable1);

        // Act
        $this->machine->execute('TRANSITION_NAME', $statable1);
        $this->machine->execute('TRANSITION_NAME', $statable2);
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


    private function getAppliableEntityOn($transition)
    {
        $statable = $this->getMock('carlescliment\StateMachine\Model\Statable');
        $transition->expects($this->any())
            ->method('isAppliableOn')
            ->will($this->returnValue(true));
        return $statable;
    }


    private function addTransitionToMachine($transition_name, $start_state, $end_state)
    {
        $transition = $this->getTransitionStub($transition_name, $start_state, $end_state);
        $this->machine->addTransition($transition);
        return $transition;
    }
}