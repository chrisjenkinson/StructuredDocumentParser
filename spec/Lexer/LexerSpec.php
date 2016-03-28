<?php

namespace spec\chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\Matcher\SimpleTextMatcher;
use chrisjenkinson\StructuredDocumentParser\State\InitialState;
use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;
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

    public function it_can_tokenise()
    {
        $state = new InitialState;
        $state->registerMatcher(new SimpleTextMatcher);

        $this->beConstructedWith($state);

        $this->tokenise('1234')->shouldReturnAnInstanceOf(TokenStream::class);
    }

    public function it_can_switch_state()
    {
        $origState = new InitialState;
        $origState->registerMatcher(new SimpleTextMatcher);

        $newState = new InitialState;
        $newState->registerMatcher(new SimpleTextMatcher);

        $this->beConstructedWith($origState);

        $this->getState()->shouldReturn($origState);

        $this->setState($newState);

        $this->getState()->shouldReturn($newState);
    }

    public function it_records_previous_states()
    {
        $origState = new InitialState;
        $origState->registerMatcher(new SimpleTextMatcher);

        $newState = new InitialState;
        $newState->registerMatcher(new SimpleTextMatcher);

        $this->beConstructedWith($origState);

        $this->setState($newState);

        $this->getLastState()->shouldReturn($origState);
    }
}
