<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use PhpSpec\ObjectBehavior;

class InitialStateSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\State\InitialState');
    }

    public function it_has_a_name()
    {
        $this->getName()->shouldReturn('InitialState');
    }
}
