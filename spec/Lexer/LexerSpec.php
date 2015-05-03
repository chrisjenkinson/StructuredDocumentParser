<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\Lexer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LexerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\Lexer\Lexer');
    }

    public function it_should_throw_exception_if_no_tokens_matched()
    {
        $this->shouldThrow('\RuntimeException')->duringTokenise('Something 1234');
    }
}
