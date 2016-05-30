<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\Token;

use PhpSpec\ObjectBehavior;

class TokenPositionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(10, 15);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\Token\TokenPosition');
    }

    public function it_has_a_line_number()
    {
        $this->getLine()->shouldReturn(10);
    }

    public function it_has_a_column_number()
    {
        $this->getColumn()->shouldReturn(15);
    }
}
