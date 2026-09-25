<?php

declare(strict_types=1);

namespace spec\chrisjenkinson\StructuredDocumentParser\Lexer;

use chrisjenkinson\StructuredDocumentParser\Finder\RegexFinder;
use chrisjenkinson\StructuredDocumentParser\Lexer\Lexer;
use chrisjenkinson\StructuredDocumentParser\Lexer\NoPreviousStateException;
use chrisjenkinson\StructuredDocumentParser\Lexer\ZeroLengthTokenLoopException;
use chrisjenkinson\StructuredDocumentParser\Matcher\AbstractMatcher;
use chrisjenkinson\StructuredDocumentParser\Matcher\MatchedText;
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

    public function it_throws_if_there_is_no_previous_state(): void
    {
        $this->shouldThrow(NoPreviousStateException::class)->during('getLastState');
    }

    public function it_pops_back_to_the_previous_state(): void
    {
        $firstState  = new InitialState();
        $secondState = new InitialState();
        $thirdState  = new InitialState();

        $this->beConstructedWith($firstState);

        $this->setState($secondState);
        $this->setState($thirdState);

        $this->popState();

        $this->getState()->shouldReturn($secondState);
        $this->getLastState()->shouldReturn($firstState);

        $this->popState();

        $this->getState()->shouldReturn($firstState);
        $this->shouldThrow(NoPreviousStateException::class)->during('getLastState');
    }

    public function it_throws_when_popping_without_a_previous_state(): void
    {
        $this->shouldThrow(NoPreviousStateException::class)->during('popState');
    }

    public function it_switches_state_on_a_zero_length_lookahead_match(): void
    {
        $origState = new InitialState();
        $newState  = new InitialState();

        $origState->registerMatcher(new LexerSpecRegexMatcher('Letter', '/(?<all>a)/A'));
        $origState->registerMatcher(new LexerSpecRegexMatcher('Lookahead', '/(?<all>)(?=b)/A'), function (Lexer $lexer) use ($newState): void {
            $lexer->setState($newState);
        });
        $newState->registerMatcher(new LexerSpecRegexMatcher('Rest', '/(?<all>b+)/A'));

        $this->beConstructedWith($origState);

        $this->tokenise('abb')->__toString()->shouldReturn("Letter (a)\nLookahead ()\nRest (bb)");
    }

    public function it_throws_if_a_zero_length_match_does_not_switch_state(): void
    {
        $state = new InitialState();
        $state->registerMatcher(new LexerSpecRegexMatcher('Empty', '/(?<all>)/A'));

        $this->beConstructedWith($state);

        $this->shouldThrow(new ZeroLengthTokenLoopException('InitialState', 0))->during('tokenise', ['a']);
    }

    public function it_throws_if_zero_length_matches_return_to_a_state_without_advancing(): void
    {
        $firstState  = new InitialState();
        $secondState = new InitialState();

        $firstState->registerMatcher(new LexerSpecRegexMatcher('ToSecond', '/(?<all>)/A'), function (Lexer $lexer) use ($secondState): void {
            $lexer->setState($secondState);
        });
        $secondState->registerMatcher(new LexerSpecRegexMatcher('ToFirst', '/(?<all>)/A'), function (Lexer $lexer) use ($firstState): void {
            $lexer->setState($firstState);
        });

        $this->beConstructedWith($firstState);

        $this->shouldThrow(new ZeroLengthTokenLoopException('InitialState', 0))->during('tokenise', ['a']);
    }
}

class LexerSpecRegexMatcher extends AbstractMatcher
{
    private RegexFinder $finder;

    public function __construct(private string $type, string $pattern)
    {
        $this->finder = new RegexFinder($pattern);
    }

    public function match(string $text): ?MatchedText
    {
        if ($this->finder->find($text)) {
            return new MatchedText($this->finder->getMatches(['all']));
        }

        return null;
    }

    public function getName(): string
    {
        return $this->type.'Matcher';
    }
}
