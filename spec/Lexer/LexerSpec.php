<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LexerSpec extends ObjectBehavior
{
    public function let(StateInterface $state)
    {
        $this->beConstructedWith($state);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('chrisjenkinson\StructuredDocumentParser\Lexer\Lexer');
    }

    public function it_has_a_state()
    {
        $this->getState()->shouldBeAnInstanceOf(StateInterface::class);
    }
}
