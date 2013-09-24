<?php

namespace StateMachine\Test\Model;

use carlescliment\StateMachine\Model\Transition;

class TransitionTest extends \PHPUnit_Framework_TestCase
{

    const PREVIOUS_STATE = 'PREVIOUS_STATE';
    const NEXT_STATE = 'NEXT_STATE';
    const NAME = 'TRANSITION_NAME';

    private $transition;

    public function setUp()
    {
        $this->transition = new Transition(self::NAME, self::PREVIOUS_STATE, self::NEXT_STATE);
    }


    /**
     * @test
     */
    public function itHasAName()
    {
        // Arrange
        // Act
        $name = $this->transition->getName();

        // Expect
        $this->assertEquals(self::NAME, $name);
    }


    /**
     * @test
     */
    public function itTransitesEntitiesFromPreviousToNext()
    {
        // Arrange
        $statable = $this->getStatableMock(self::PREVIOUS_STATE);

        // Assert
        $statable->expects($this->once())
            ->method('setState')
            ->with(self::NEXT_STATE);

        // Act
        $this->transition->execute($statable);
    }


    /**
     * @test
     */
    public function itChecksIfItIsAppliableOnAnEntity()
    {
        // Arrange
        $appliable_statable = $this->getStatableMock(self::PREVIOUS_STATE);
        $unappliable_statable = $this->getStatableMock('INVALID_STATE');

        // Assert
        $expected_appliable = $this->transition->isAppliableOn($appliable_statable);
        $expected_unappliable = $this->transition->isAppliableOn($unappliable_statable);

        // Act
        $this->assertTrue($expected_appliable);
        $this->assertFalse($expected_unappliable);
    }


    /**
     * @test
     * @expectedException carlescliment\StateMachine\Exception\InvalidTransitionException
     */
    public function itRaisesAnErrorWhenTransitingFromInvalidStates()
    {
        // Arrange
        $statable = $this->getStatableMock('INVALID_STATE');

        // Act
        $this->transition->execute($statable);
    }


    private function getStatableMock($previous_state)
    {
        $statable = $this->getMock('carlescliment\StateMachine\Model\Statable');
        $statable->expects($this->any())
            ->method('getState')
            ->will($this->returnValue($previous_state));
        return $statable;
    }
}