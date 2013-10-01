State Machine
=============

This is a simple, no-deps PHP state machine.

## Installation

Update your `composer.json` file adding the following dependency and run `php composer.phar update carlescliment/state-machine`:

        "carlescliment/state-machine": "0.0.4"

## Usage

Implementing a state machine is straight-forward. You need four components; an statable object, states, transitions and the state machine.


    <?php

    use carlescliment\StateMachine\Model\Statable,
        carlescliment\StateMachine\Model\StateMachine,
        carlescliment\StateMachine\Model\Transition;


    class SemaphoreStates
    {
        const OPEN = 'semaphore.open';
        const WARNING = 'semaphore.warning';
        const LOCKED = 'semaphore.locked';

        public static function all()
        {
            return array(self::OPEN, self::WARNING, self::LOCKED);
        }
    }


    class SemaphoreTransitions
    {
        const GIVE_PASS = 'semaphore.give_pass';
        const WARN = 'semaphore.warn';
        const LOCK = 'semaphore.lock';
    }


    class Semaphore implements Statable
    {
        private $state = SemaphoreStates::LOCKED;

        public function getState()
        {
            return $this->state;
        }

        public function setState($state)
        {
            $this->state = $state;
            return $this;
        }
    }


    class SemaphoreStateMachine extends StateMachine
    {
        public function __construct()
        {
            parent::construct();

            foreach (SemaphoreStates::all() as $state) {
                $this->addState($state);
            }

            $transitions = array(
                new Transition(AuctionTransitions::GIVE_PASS, AuctionStates::LOCKED, AuctionStates::OPEN),
                new Transition(AuctionTransitions::GIVE_PASS, AuctionStates::WARNING, AuctionStates::OPEN),
                new Transition(AuctionTransitions::WARN, AuctionStates::OPEN, AuctionStates::WARNING),
                new Transition(AuctionTransitions::WARN, AuctionStates::LOCKED, AuctionStates::WARNING),
                new Transition(AuctionTransitions::LOCK, AuctionStates::OPEN, AuctionStates::LOCKED),
                new Transition(AuctionTransitions::LOCK, AuctionStates::WARNING, AuctionStates::LOCKED),
                );
            foreach ($transitions as $transition) {
                $this->addTransition($transition);
            }
        }
    }

Now, you can operate with your state machine:

    <?php

    $semaphore = new Semaphore;

    $machine = new SemaphoreStateMachine;
    $machine->execute(SemaphoreTransitions::GIVE_PASS, $semaphore);
    $machine->execute(SemaphoreTransitions::WARN, $semaphore);
    $machine->execute(SemaphoreTransitions::LOCK, $semaphore);


