<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use PhpSpec\ObjectBehavior;

class NoTokenFoundExceptionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('stateName', 50, 'remainingText');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\State\NoTokenFoundException');
    }

    public function it_is_an_exception()
    {
        $this->shouldHaveType(\RuntimeException::class);
    }

    public function it_has_the_state_name()
    {
        $this->getStateName()->shouldReturn('stateName');
    }

    public function it_has_the_current_position()
    {
        $this->getCurrentPosition()->shouldReturn(50);
    }

    public function it_has_the_remaining_text()
    {
        $this->getRemainingText()->shouldReturn('remainingText');
    }
}
