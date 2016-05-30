<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\Token;

use PhpSpec\ObjectBehavior;

class NonexistentKeyExceptionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\Token\NonexistentKeyException');
    }

    public function it_is_an_exception()
    {
        $this->shouldHaveType(\RuntimeException::class);
    }
}
