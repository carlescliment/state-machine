<?php

namespace StateMachine\Test\Model;

use carlescliment\StateMachine\Model\Transition;

class TransitionTest extends \PHPUnit_Framework_TestCase
{

    const PREVIOUS_STATE = 'PREVIOUS_STATE';
    const NEXT_STATE = 'NEXT_STATE';

    private $transition;

    public function setUp()
    {
        $this->transition = new Transition(self::PREVIOUS_STATE, self::NEXT_STATE);
    }


    /**
     * @test
     */
    public function itTransitesEntitiesFromPreviousToNext()
    {
        // Arrange
        $transitable = $this->getMock('carlescliment\StateMachine\Model\Statable');
        $transitable->expects($this->any())
            ->method('getState')
            ->will($this->returnValue(self::PREVIOUS_STATE));

        // Assert
        $transitable->expects($this->once())
            ->method('setState')
            ->with(self::NEXT_STATE);

        // Act
        $this->transition->transit($transitable);
    }


    /**
     * @test
     * @expectedException carlescliment\StateMachine\Exception\InvalidTransitionException
     */
    public function itRaisesAnErrorWhenTransitingFromInvalidStates()
    {
        // Arrange
        $transitable = $this->getMock('carlescliment\StateMachine\Model\Statable');
        $transitable->expects($this->any())
            ->method('getState')
            ->will($this->returnValue('INVALID_STATE'));

        // Act
        $this->transition->transit($transitable); 
    }
}