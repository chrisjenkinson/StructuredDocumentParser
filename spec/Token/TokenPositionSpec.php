<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Token;

use PhpSpec\ObjectBehavior;

class TokenPositionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(10, 15);
    }

    public function it_has_a_line_number(): void
    {
        $this->getLine()->shouldReturn(10);
    }

    public function it_has_a_column_number(): void
    {
        $this->getColumn()->shouldReturn(15);
    }
}
