<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\Matcher\SimpleTextMatcher;
use chrisjenkinson\StructuredDocumentParser\State\InitialState;
use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenStream;
use PhpSpec\ObjectBehavior;

class LexerSpec extends ObjectBehavior
{
    public function let(StateInterface $state): void
    {
        $this->beConstructedWith($state);
    }

    public function it_has_a_state(): void
    {
        $this->getState()->shouldBeAnInstanceOf(StateInterface::class);
    }

    public function it_can_tokenise(): void
    {
        $state = new InitialState();
        $state->registerMatcher(new SimpleTextMatcher());

        $this->beConstructedWith($state);

        $this->tokenise('1234')->shouldReturnAnInstanceOf(TokenStream::class);
    }

    public function it_can_switch_state(): void
    {
        $origState = new InitialState();
        $origState->registerMatcher(new SimpleTextMatcher());

        $newState = new InitialState();
        $newState->registerMatcher(new SimpleTextMatcher());

        $this->beConstructedWith($origState);

        $this->getState()->shouldReturn($origState);

        $this->setState($newState);

        $this->getState()->shouldReturn($newState);
    }

    public function it_records_previous_states(): void
    {
        $origState = new InitialState();
        $origState->registerMatcher(new SimpleTextMatcher());

        $newState = new InitialState();
        $newState->registerMatcher(new SimpleTextMatcher());

        $this->beConstructedWith($origState);

        $this->setState($newState);

        $this->getLastState()->shouldReturn($origState);
    }
}
