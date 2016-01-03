<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NoTokenFoundExceptionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('stateName', 0, 'remainingText');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\State\NoTokenFoundException');
    }

    public function it_is_an_exception()
    {
        $this->shouldHaveType(\RuntimeException::class);
    }
}
