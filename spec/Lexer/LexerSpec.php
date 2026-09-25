<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\Lexer\Cursor;
use chrisjenkinson\StructuredDocumentParser\Lexer\NoPreviousStateException;
use chrisjenkinson\StructuredDocumentParser\Lexer\ZeroLengthTokenLoopException;
use chrisjenkinson\StructuredDocumentParser\State\StateInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenInterface;
use chrisjenkinson\StructuredDocumentParser\Token\TokenPosition;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RuntimeException;

class LexerSpec extends ObjectBehavior
{
    public function let(StateInterface $state): void
    {
        $state->getName()->willReturn('FirstState');

        $this->beConstructedWith($state);
    }

    public function it_has_a_state(StateInterface $state): void
    {
        $this->getState()->shouldReturn($state);
    }

    public function it_pushes_a_state_and_records_the_previous_one(StateInterface $state, StateInterface $newState): void
    {
        $this->pushState($newState);

        $this->getState()->shouldReturn($newState);
        $this->getLastState()->shouldReturn($state);
    }

    public function it_keeps_set_state_as_an_alias_for_push_state(StateInterface $state, StateInterface $newState): void
    {
        $this->setState($newState);

        $this->getState()->shouldReturn($newState);
        $this->getLastState()->shouldReturn($state);
    }

    public function it_throws_if_there_is_no_previous_state(): void
    {
        $this->shouldThrow(NoPreviousStateException::class)->during('getLastState');
    }

    public function it_pops_back_to_the_previous_state(StateInterface $state, StateInterface $secondState, StateInterface $thirdState): void
    {
        $this->pushState($secondState);
        $this->pushState($thirdState);

        $this->popState();

        $this->getState()->shouldReturn($secondState);
        $this->getLastState()->shouldReturn($state);

        $this->popState();

        $this->getState()->shouldReturn($state);
        $this->shouldThrow(NoPreviousStateException::class)->during('getLastState');
    }

    public function it_throws_when_popping_without_a_previous_state(): void
    {
        $this->shouldThrow(NoPreviousStateException::class)->during('popState');
    }

    public function it_asks_the_state_for_tokens_until_the_text_is_consumed(StateInterface $state, TokenInterface $first, TokenInterface $second): void
    {
        $first->getText()->willReturn('ab');
        $second->getText()->willReturn('c');

        $state->findMatchingToken($this, $this->cursorAt('abc'))->willReturn($first);
        $state->findMatchingToken($this, $this->cursorAt('c'))->willReturn($second);

        $tokens = $this->tokenise('abc');

        $tokens->consumeToken()->shouldReturn($first);
        $tokens->consumeToken()->shouldReturn($second);
        $tokens->shouldHaveCount(0);
    }

    public function it_starts_the_cursor_at_line_one_column_one_by_default(StateInterface $state, TokenInterface $token): void
    {
        $token->getText()->willReturn('a');

        $state->findMatchingToken($this, Argument::that(static fn (Cursor $cursor): bool => 1 === $cursor->getLine() && 1 === $cursor->getColumn()))
            ->shouldBeCalled()
            ->willReturn($token);

        $this->tokenise('a');
    }

    public function it_starts_the_cursor_at_a_given_position(StateInterface $state, TokenInterface $token): void
    {
        $token->getText()->willReturn('a');

        $state->findMatchingToken($this, Argument::that(static fn (Cursor $cursor): bool => 10 === $cursor->getLine() && 5 === $cursor->getColumn()))
            ->shouldBeCalled()
            ->willReturn($token);

        $this->tokenise('a', new TokenPosition(10, 5));
    }

    public function it_allows_a_zero_length_token_that_switches_state(StateInterface $state, StateInterface $newState, TokenInterface $lookahead, TokenInterface $letter): void
    {
        $lookahead->getText()->willReturn('');
        $letter->getText()->willReturn('a');

        $state->findMatchingToken($this, Argument::type(Cursor::class))->will(static function (array $arguments) use ($newState, $lookahead): TokenInterface {
            $arguments[0]->pushState($newState->getWrappedObject());

            return $lookahead->getWrappedObject();
        });
        $newState->findMatchingToken($this, Argument::type(Cursor::class))->willReturn($letter);

        $this->tokenise('a')->shouldHaveCount(2);
    }

    public function it_throws_if_a_zero_length_token_does_not_switch_state(StateInterface $state, TokenInterface $token): void
    {
        $token->getText()->willReturn('');

        $state->findMatchingToken($this, Argument::type(Cursor::class))->willReturn($token);

        $this->shouldThrow(new ZeroLengthTokenLoopException('FirstState', 0))->during('tokenise', ['a']);
    }

    public function it_throws_if_zero_length_tokens_return_to_a_state_without_advancing(StateInterface $state, StateInterface $secondState, TokenInterface $token): void
    {
        $token->getText()->willReturn('');

        $state->findMatchingToken($this, Argument::type(Cursor::class))->will(static function (array $arguments) use ($secondState, $token): TokenInterface {
            $arguments[0]->pushState($secondState->getWrappedObject());

            return $token->getWrappedObject();
        });
        $secondState->findMatchingToken($this, Argument::type(Cursor::class))->will(static function (array $arguments) use ($state, $token): TokenInterface {
            $arguments[0]->pushState($state->getWrappedObject());

            return $token->getWrappedObject();
        });

        $this->shouldThrow(new ZeroLengthTokenLoopException('FirstState', 0))->during('tokenise', ['a']);
    }

    public function it_starts_each_call_from_the_initial_state(StateInterface $state, StateInterface $newState, TokenInterface $token): void
    {
        $token->getText()->willReturn('a');

        $state->findMatchingToken($this, Argument::type(Cursor::class))->will(static function (array $arguments) use ($newState, $token): TokenInterface {
            $arguments[0]->pushState($newState->getWrappedObject());

            return $token->getWrappedObject();
        });

        $this->tokenise('a');
        $this->tokenise('a');

        $state->findMatchingToken($this, Argument::type(Cursor::class))->shouldHaveBeenCalledTimes(2);
        $newState->findMatchingToken(Argument::cetera())->shouldNotHaveBeenCalled();
    }

    public function it_restores_the_initial_state_when_tokenising_fails(StateInterface $state, StateInterface $newState): void
    {
        $state->findMatchingToken($this, Argument::type(Cursor::class))->will(static function (array $arguments) use ($newState): never {
            $arguments[0]->pushState($newState->getWrappedObject());

            throw new RuntimeException('No token');
        });

        $this->shouldThrow(RuntimeException::class)->during('tokenise', ['a']);

        $this->getState()->shouldReturn($state);
        $this->shouldThrow(NoPreviousStateException::class)->during('getLastState');
    }

    private function cursorAt(string $remainingText): Argument\Token\CallbackToken
    {
        return Argument::that(static fn (Cursor $cursor): bool => $remainingText === $cursor->getRemainingText());
    }
}
