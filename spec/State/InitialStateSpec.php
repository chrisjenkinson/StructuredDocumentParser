<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\State;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InitialStateSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\State\InitialState');
    }
}
