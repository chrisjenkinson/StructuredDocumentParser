<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use PhpSpec\ObjectBehavior;
use RuntimeException;

class NoTokenFoundExceptionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('stateName', 50, 'remainingText');
    }

    public function it_is_an_exception(): void
    {
        $this->shouldHaveType(RuntimeException::class);
    }

    public function it_has_the_state_name(): void
    {
        $this->getStateName()->shouldReturn('stateName');
    }

    public function it_has_the_current_position(): void
    {
        $this->getCurrentPosition()->shouldReturn(50);
    }

    public function it_has_the_remaining_text(): void
    {
        $this->getRemainingText()->shouldReturn('remainingText');
    }
}
